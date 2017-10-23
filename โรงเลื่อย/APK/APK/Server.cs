using System;
using System.IO;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Newtonsoft.Json.Linq;
using MySql.Data.MySqlClient;
using System.Net;
using System.Net.Http;

namespace APK
{
    class Server
    {
        string weight_no;
        public string getMysql()
        {
            string servername = "Server = 27.254.36.4;";
            string database = "Database = revoclou_afm;";
            string username = "Uid = revoclou_afm;";
            string password = "Pwd = ?AL2cP4l#o?X;";
            string ConnMySqlDatabase = servername + database + username + password + "CharSet=utf8;";
            return ConnMySqlDatabase;
        }
        public string getLastId(string saw_id)
        {
            /*
            HttpWebRequest request = (HttpWebRequest)WebRequest.Create("http://afm.revocloudserver.com/api/transaction/max-number");
            request.ContentType = "application/json; charset=utf-8";
            request.PreAuthenticate = true;
            HttpWebResponse response = request.GetResponse() as HttpWebResponse;
            //Console.WriteLine(response);
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
                var getweightNo = (string)obj[1][0]["weight_no"];
                //Console.WriteLine(getweightNo);
                string result = (string)getweightNo;
                return result;
                */
            
            string ConnMySqlDatabase = this.getMysql();
            MySqlConnection mysqlconn = null;

            try
            {
                mysqlconn = new MySqlConnection(ConnMySqlDatabase);
            }
            catch (Exception ex)
            {
                Console.WriteLine("Error can't connect with database. {0}", ex.Message);
                return ex.Message;
            }
            try
            {
                mysqlconn.Open();
            }
            catch (Exception ex)
            {
                Console.WriteLine("Can't connect with database sql.{0}", ex.Message);
                return ex.Message;
            }
            try
            {
                
                string sql = "select weight_no FROM `raw` WHERE saw_id='"+saw_id+"' ORDER BY weight_no DESC LIMIT 0,1";
                MySqlCommand cmd = new MySqlCommand(sql, mysqlconn);
                Console.WriteLine(sql);
                MySqlDataReader reader = cmd.ExecuteReader();
                
                while (reader.Read())
                {
                    weight_no = reader["weight_no"].ToString();
                    //Console.WriteLine(weight_no);
                }

            }
            catch (Exception ex)
            {
                Console.WriteLine("Error: {0}", ex.ToString());
                //Console.WriteLine("Can't insert data to database.{0}", ex.Message);
            }
            finally
            {
                // if (mysqlconn.State == ConnectionState.Open)
                // {
                mysqlconn.Close();
                // }
            }
            return weight_no;

        }//lastId

        public string addData(
string saw_id,
string weight_no,
string sequence,
string car_register,
string cus_id,
string cus_name,
string pro_id,
string pro_name,
string pro_unit,
string pro_kg_unit,
string pro_price_kg,
string place_id,
string place_name,
string weight_in,
string datetime_in,
string user_in,
string weight_out,
string datetime_out,
string user_out,
string weight_net,
string weight_tare,
string weight_total,
string price,
string price_total,
string type_id,
string type_name,
string weight_desc,
string evaluation,
string lan_id,
string lan_name,
string suan_id,
string suan_name,
string receive_id,
string bill_no,
string percent_tare,
string percent_vat,
string group_stock,
string PicPath
            )
        {
            string ConnMySqlDatabase =this.getMysql();
            MySqlConnection mysqlconn = null;
            MySqlCommand cmd;
            try
            {
                mysqlconn = new MySqlConnection(ConnMySqlDatabase);
            }
            catch (Exception ex)
            {
                Console.WriteLine("Error can't connect with database. {0}", ex.Message);
                return ex.Message;
            }
            try
            {
                mysqlconn.Open();
            }
            catch (Exception ex)
            {
                Console.WriteLine("Can't connect with database sql.{0}", ex.Message);
                return ex.Message;
            }
            try
            {

                cmd = mysqlconn.CreateCommand();

                cmd.CommandText = "INSERT INTO raw(saw_id,weight_no,sequence,car_register,cus_id,cus_name,pro_id,pro_name,pro_unit,pro_kg_unit,pro_price_kg,place_id,place_name,weight_in,datetime_in,user_in,weight_out,datetime_out,user_out,weight_net,weight_tare,weight_total,price,price_total,type_id,type_name,weight_desc,evaluation,lan_id,lan_name,suan_id,suan_name,receive_id,bill_no,percent_tare,percent_vat,group_stock,PicPath) VALUES ('"
                    + saw_id + "','"
                    + weight_no + "','"
                    + sequence + "','"
                    + car_register + "','"
                    + cus_id + "','"
                    + cus_name + "','"
                    + pro_id + "','"
                    + pro_name + "','"
                    + pro_unit + "','"
                    + pro_kg_unit + "','"
                    + pro_price_kg + "','"
                    + place_id + "','"
                    + place_name + "','"
                    + weight_in + "','"
                    + datetime_in + "','"
                    + user_in + "','"
                    + weight_out + "','"
                    + datetime_out + "','"
                    + user_out + "','"
                    + weight_net + "','"
                    + weight_tare + "','"
                    + weight_total + "','"
                    + price + "','"
                    + price_total + "','"
                    + type_id + "','"
                    + type_name + "','"
                    + weight_desc + "','"
                    + evaluation + "','"
                    + lan_id + "','"
                    + lan_name + "','"
                    + suan_id + "','"
                    + suan_name + "','"
                    + receive_id + "','"
                    + bill_no + "','"
                    + percent_tare + "','"
                    + percent_vat + "','"
                    + group_stock + "','"
                    + PicPath + "')";
                cmd.ExecuteNonQuery();
                /*
                Form1 frm = new Form1();
                frm.label2.Text = string.Format("weight_no :{0}", weight_no);
                frm.label2.Update();
                */
            }
            catch (Exception ex)
            {
                
                Console.WriteLine("Can't insert data to database.{0}", ex.Message);
            }
            finally
            {
               // if (mysqlconn.State == ConnectionState.Open)
               // {
                    mysqlconn.Close();
               // }
            }
            string adddata = "add  data";
            return adddata;
        }//addData

        /*
        public void uploadImage(string weight_no,string fileImage)
        {
            httpMethod http = new HttpMethod();
            if (!string.IsNullOrEmpty(fileImage))
            {
                string[] images = fileImage.Split("|".ToCharArray());
                List<string> data = new List<string>();
                foreach (string image in images)
                {
                    if (File.Exists(image))
                    {
                        //files exist
                        if (!string.IsNullOrEmpty(image))
                        {
                            string imageURL = http.postUploadImage(weight_no, image);
                            string replaceString = image.Replace('\\', '/');
                            
                            if (imageURL.Contains("http://"))
                            {
                                data.Add(imageURL);
                            }
                        }
                        else
                        {
                            if (!string.IsNullOrEmpty(image))
                            {
                                string imageURL = http.postUploadImage(weight_no, image);
                                string replaceString = image.Replace('\\', '/');
                                
                                if (imageURL.Contains("http://"))
                                {
                                    data.Add(imageURL);
                                }
                            }
                        }
                    }
                try
            {
                WebClient client = new WebClient();
                string myFile = @"D:\test_file.txt";
                client.Credentials = CredentialCache.DefaultCredentials;
                client.UploadFile(@"http://localhost/uploads/upload.php", "POST", path);
                client.Dispose();
            }
            catch (Exception err)
            {
                MessageBox.Show(err.Message);
            }
        }
        */


    }//class
}//namespace
