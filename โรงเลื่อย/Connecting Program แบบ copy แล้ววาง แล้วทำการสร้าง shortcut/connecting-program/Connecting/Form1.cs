using System;
using System.Data;
using System.Data.OleDb;
using System.IO;
using System.Windows.Forms;
using System.Drawing;
using System.Net;
using System.Text;
using Newtonsoft.Json.Linq;
using System.Collections.Generic;
using MySql.Data.MySqlClient;

namespace Connecting
{
    public partial class Form1 : Form
    {
        public string weightNumber;
        public string row;
        public Form1()
        {
            InitializeComponent();
            statusBG.BackColor = Color.Red;
            statuslbl.Text = "Wating for connect . . .";
            getWeightNumber();
        }
        //disable close button on window form
        private const int CP_NOCLOSE_BUTTON = 0x200;
        protected override CreateParams CreateParams
        {
            get
            {
                CreateParams myCp = base.CreateParams;
                myCp.ClassStyle = myCp.ClassStyle | CP_NOCLOSE_BUTTON;
                return myCp;
            }
        }
        //count 5 min for call method again
        private Timer timer1;
        private void InitTimer()
        {
            timer1 = new Timer();
            timer1.Tick += new EventHandler(timer1_Tick);
            timer1.Interval = 300000; // in miliseconds
            timer1.Start();
        }

        private void timer1_Tick(object sender, EventArgs e)
        {
            getWeightNumber();
        }
        private void getWeightNumber ()
        {
            HttpWebRequest request = (HttpWebRequest)WebRequest.Create("http://afm.revocloudserver.com/api/transaction/max-number");
            request.ContentType = "application/json; charset=utf-8";
            request.PreAuthenticate = true;
            HttpWebResponse response = request.GetResponse() as HttpWebResponse;
            if (response.StatusCode != HttpStatusCode.OK)
                throw new Exception(string.Format(
                "Server error (HTTP {0}: {1}).",
                response.StatusCode,
                response.StatusDescription));
            using (Stream responseStream = response.GetResponseStream())
            {
                StreamReader reader = new StreamReader(responseStream, Encoding.UTF8);
                string json = reader.ReadToEnd();
                var obj = JArray.Parse(json);
                var status = (string)obj[0][0]["data"]["status"];
                if (status.Equals("true"))
                {
                    var getweightNo = (string)obj[1][0]["weight_no"];
                    weightNumber = getweightNo;
                    woodPieces wp = new woodPieces();
                    fireWood fw = new fireWood();
                    weightOutcoming wo = new weightOutcoming();
                    if (!string.IsNullOrEmpty(weightNumber))
                    {
                        weightNumber = getweightNo;
                        wp.connAccessToWoodPiecesDatabase();
                        wp.connAccessToWoodPiecesOutcomeDatabase();
                        fw.connAccessToFireWoodDatabase();
                        fw.connAccessToFireWoodOutcomeDatabase();
                        wo.connAccessToWeightOutcomingDatabase();
                        wo.connAccessToWeightOutcomingSlabDatabase();
                        wo.connAccessToWeightOutcomingSawdustDatabase();
                        connAccessDatabase(getweightNo);
                    }
                    else
                    {
                        weightNumber = "0";
                        wp.connAccessToWoodPiecesDatabase();
                        wp.connAccessToWoodPiecesOutcomeDatabase();
                        fw.connAccessToFireWoodDatabase();
                        fw.connAccessToFireWoodOutcomeDatabase();
                        wo.connAccessToWeightOutcomingDatabase();
                        wo.connAccessToWeightOutcomingSlabDatabase();
                        wo.connAccessToWeightOutcomingSawdustDatabase();
                        connAccessDatabase("0");
                    }
                    InitTimer();
                }
                else
                {
                    var message = (string)obj[0][0]["data"]["message"];
                    statuslbl.Text = message;
                    weightNumber = "0";
                    connAccessDatabase("0");
                }
            }
        }

        private void connAccessDatabase(string weightNo)
        {
            //get current date 
            //DateTime currentDate = DateTime.Today;
            //string setCurrentDate = string.Format(currentDate.ToString("yyMMdd"));
            //set current date with OB (tablename in access)
            //string dbTable = "OB" + setCurrentDate;
            httpMethod http = new httpMethod();
            connectString connSTR;
            
            if (File.Exists(@"C:\config.ini"))
            {
                string[] configtxt = File.ReadAllLines(@"C:\config.ini");
                if (configtxt[0].Contains(";DBTYPE = \"ACCESS\""))
                {
                    connSTR = new connectString(configtxt[1], configtxt[2], configtxt[3], configtxt[4]);
                }
                else
                {
                    connSTR = new connectString(configtxt[0], configtxt[2], configtxt[3], configtxt[4]);
                }
                string sawId = configtxt[5].After("=").Trim();
                string shortname = configtxt[6].After("=").Trim();
                string databasePath = configtxt[4].After("=").Trim();
                if (File.Exists(databasePath))
                {
                    // Console.WriteLine(connSTR.cmdConnectString);
                    if (connSTR.databaseName.Contains("ACCESS"))
                    {
                        string connectSTR = connSTR.cmdConnectString;
                        string queryCommand = string.Format("select * FROM tbl_weight where(weight_no > {0}) order By weight_no asc", weightNo);
                        DataSet mData = new DataSet();
                        OleDbConnection accessConn = null;
                        connectionLog conLog = new connectionLog();
                        try
                        {
                            accessConn = new OleDbConnection(connectSTR);
                            statuslbl.Text = "start connecting";
                            conLog.insertConnectionLog(shortname, "access", "success", "Start connecting");
                        }
                        catch (Exception ex)
                        {
                            //Error to connect access database.Please check access database command.
                            if (statuslbl.InvokeRequired)
                            {
                                statuslbl.Invoke(new MethodInvoker(delegate { statuslbl.Text = ex.Message; }));
                                string message = string.Format("Error to connect access database.Please check access database command : {0}", ex.Message);
                                conLog.insertConnectionLog(shortname, "access", "failed", message);
                            }
                            statusBG.BackColor = Color.Red;
                            return;
                        }
                        try
                        {
                            accessConn.Open();
                            statuslbl.Text = "connecting open";
                            conLog.insertConnectionLog(shortname, "access", "success", "Connecting Open");
                        }
                        catch (Exception ex)
                        {
                            //can't connect with database
                            statuslbl.Text = ex.Message;
                            statusBG.BackColor = Color.Red;
                            string message = string.Format("Can't connect with database : {0}", ex.Message);
                            conLog.insertConnectionLog(shortname, "access", "failed", message);
                            return;
                        }
                        try
                        {
                            OleDbCommand accessCommand = new OleDbCommand(queryCommand, accessConn);
                            OleDbDataAdapter myDataAdapter = new OleDbDataAdapter(accessCommand);
                            myDataAdapter.Fill(mData, "tbl_weight");
                            conLog.insertConnectionLog(shortname, "access", "success", "Check table in database and field");
                            statuslbl.Text = "Check table in database.";
                            statusBG.BackColor = Color.Red;
                        }
                        catch (Exception ex)
                        {
                            //Error failed to retrieve the required data from database.
                            statuslbl.Text = ex.Message;
                            statusBG.BackColor = Color.Red;
                            string message = string.Format("Error failed to retrieve the required data from database : {0}", ex.Message);
                            conLog.insertConnectionLog(shortname, "access", "failed", message);
                            return;
                        }
                        finally
                        {
                            accessConn.Close();
                            statuslbl.Text = "connecting success";
                            statusBG.BackColor = Color.Green;
                            conLog.insertConnectionLog(shortname, "access", "success", "Connecting success");
                        }
                        DataRowCollection dra = mData.Tables["tbl_weight"].Rows;
                        row = string.Format("{0}", dra.Count); //count row in access database when insert to mysql database
                        foreach (DataRow dr in dra)
                        {
                            /*
                            string weight_no
                            string fileImage,
                            string sawId,
                            string productId,
                            string truck_register_number,
                            string customer,
                            string datetime_in,
                            string weight_in,
                            string datetime_out,
                            string weight_out,
                            string weight_net,
                            string weight_tare,
                            string weight_total,
                            string product_price_unit,
                            string price_total) */

                            if (string.Format("{0}", dr[24]).Equals("รับเชื้อเพลิง"))
                            {
                                //incoming
                                uploadImage(string.Format("{0}", dr[0]), //string weight_no
                                string.Format("{0}", dr[36]),//string fileImage
                                sawId, //string sawId
                                string.Format("{0}", dr[5]), // string productId
                                string.Format("{0}", dr[2]), //string truck_register_number
                                string.Format("{0}", dr[4]), // string customer
                                convertToDatetimeString(string.Format("{0}", dr[13])), //string datetime_in
                                string.Format("{0}", dr[12]), //string weight_in
                                convertToDatetimeString(string.Format("{0}", dr[16])), //string datetime_out
                                string.Format("{0}", dr[15]), //string weight_out
                                string.Format("{0}", dr[18]), // string weight_net
                                string.Format("{0}", dr[19]), //string weight_tare
                                string.Format("{0}", dr[20]), //string weight_total
                                string.Format("{0}", dr[9]), //string product_price_unit
                                string.Format("{0}", dr[21]), // string price_total
                                "income",
                                shortname); //shortnamesawmill
                            }
                            else
                            {
                                //outcoming
                                uploadImage(string.Format("{0}", dr[0]),
                                string.Format("{0}", dr[36]),
                                sawId,
                                string.Format("{0}", dr[5]),
                                string.Format("{0}", dr[2]),
                                string.Format("{0}", dr[4]),
                                convertToDatetimeString(string.Format("{0}", dr[13])),
                                string.Format("{0}", dr[12]),
                                convertToDatetimeString(string.Format("{0}", dr[16])),
                                string.Format("{0}", dr[15]),
                                string.Format("{0}", dr[18]),
                                string.Format("{0}", dr[19]),
                                string.Format("{0}", dr[20]),
                                string.Format("{0}", dr[9]),
                                string.Format("{0}", dr[21]),
                                "outcome",
                                shortname); //shortnamesawmill
                            }
                        }
                        //insert data into transaction table 
                        if (!string.IsNullOrEmpty(row))
                        {
                            if (!row.Equals("0"))
                            {
                                string status = http.postCheckTransaction(row);
                                if (status.Equals("true"))
                                {
                                    statuslbl.Text = "Insert into transaction success.";
                                    statusBG.BackColor = Color.Green;
                                }
                                else
                                {
                                    return;
                                }
                            }
                            else
                            {
                                statuslbl.Text = "Don't have any data to update.";
                                statusBG.BackColor = Color.Green;
                            }
                        }
                        //24
                    }
                }
                else
                {
                    
                    connectionLog conLog = new connectionLog();
                    statuslbl.Text = "Could not find access database.";
                    statusBG.BackColor = Color.Red;
                    conLog.insertConnectionLog(shortname, "access", "failed", "Could not find access database.");
                    InitTimer();
                }
            }
            else
            {
                connectionLog conLog = new connectionLog();
                statuslbl.Text = "Could not find config.ini";
                statusBG.BackColor = Color.Red;
                conLog.insertConnectionLog("APK", "access", "failed", "Could not find config.ini");
                InitTimer();
            }
        }
        private string convertToDatetimeString (string datetime)
        {
            string datetimeString = "";
            DateTime dateValue = DateTime.Parse(datetime);
            datetimeString = dateValue.ToString("yyyy-MM-dd HH:mm:ss");
            return datetimeString;
        }

        private void uploadImage (string weight_no,
            string fileImage,
            string sawId,
            string productId,
            string truck_register_number,
            string customer,
            string datetime_in,
            string weight_in,
            string datetime_out,
            string weight_out,
            string weight_net,
            string weight_tare,
            string weight_total,
            string product_price_unit,
            string price_total,
            string type_name,
            string shortnamesawmill)
        {
            transactionLog tranLog = new transactionLog();
            transaction tranData = new transaction();
            httpMethod http = new httpMethod();
            if (!string.IsNullOrEmpty(fileImage))
            {
                string[] images = fileImage.Split("|".ToCharArray());
                List<string> data = new List<string>();
                foreach (string image in images) {
                    if (File.Exists(image))
                        {
                           //files exist
                           if(!string.IsNullOrEmpty(image)) {
                               string imageURL =  http.postUploadImage(weight_no,image);
                               string replaceString = image.Replace('\\', '/');
                               tranLog.insertTransactionLog(weight_no, shortnamesawmill, "check", "success", string.Format("File exist : {0}", replaceString));
                            if (imageURL.Contains("http://"))
                            {
                                data.Add(imageURL);
                            }
                        }    
                     }
                     else
                     {
                       if(!string.IsNullOrEmpty(image))
                        {
                            string imageURL = http.postUploadImage(weight_no, image);
                            string replaceString = image.Replace('\\', '/');
                            tranLog.insertTransactionLog(weight_no, shortnamesawmill, "check", "failed", string.Format("File does not exist : {0}", replaceString));
                            if (imageURL.Contains("http://"))
                            {
                                data.Add(imageURL);
                            }
                        }
                     }
                }
                if (data.Count > 0)
                {
                    string allImage = "";
                    foreach (string img in data)
                    {
                        allImage = string.Concat(allImage, img + "|");
                    }
                    tranData.insertTransactionData(sawId,
                        productId,
                        truck_register_number,
                        weight_no,
                        customer,
                        datetime_in,
                        weight_in,
                        datetime_out,
                        weight_out,
                        weight_net,
                        weight_tare,
                        weight_total,
                        product_price_unit,
                        price_total,
                        type_name,
                        allImage,
                        shortnamesawmill);
                }
            }
        }

        //hide program when window minimized
        private void Form1_Resize(object sender, EventArgs e)
        {
            if (WindowState == FormWindowState.Minimized)
            {
                Hide();
                notifyIcon1.Icon = SystemIcons.Application;
                notifyIcon1.Visible = true;
            }
        }
        //click program on notifyicon taskbar
        private void notifyIcon1_mouseOneClicked(object sender, MouseEventArgs e)
        {
            Show();
            WindowState = FormWindowState.Normal;
            notifyIcon1.Visible = false;
        }
    }
}
