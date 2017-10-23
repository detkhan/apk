using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Data.OleDb;
using System.Net;
using System.IO;
using System.Data;
using System.Net.Http;

namespace APK
{
    class Access
    {
        public void getAccess(string weight_no1,string saw_id)
        {
            
            if (String.IsNullOrEmpty(weight_no1))
            {
                weight_no1 = "0";
            }
            Console.WriteLine(weight_no1);
            

                UtilityApk utilityApk = new UtilityApk();
            string file_access=utilityApk.getAccess();
            string conStr = "Provider=Microsoft.Jet.OLEDB.4.0; Data Source="+ file_access;
            OleDbConnection conn = new OleDbConnection(conStr);
            conn.Open();
            OleDbCommand myCommand = conn.CreateCommand();
            myCommand.CommandText = "SELECT * FROM  tbl_weight where weight_no >"+weight_no1;
            OleDbDataReader myReader = myCommand.ExecuteReader();
            
            Console.WriteLine("{0}", myCommand.CommandText);
            //Console.WriteLine("{0}", myReader.HasRows);
            if (myReader.HasRows)
            {

     
            
            while (myReader.Read())
            {


                //Console.WriteLine("{0}", myReader.GetInt32(0));
                String[] data ={
                    myReader.GetValue(0).ToString(),
                    myReader.GetValue(4).ToString(),
                    myReader.GetValue(3).ToString()
                };
                //UtilityApk utility_apk = new UtilityApk();
                string weight_no = myReader.GetValue(0).ToString();
                string sequence = myReader.GetValue(1).ToString();
                string car_register = myReader.GetValue(2).ToString();
                string cus_id = myReader.GetValue(3).ToString();
                string cus_name = myReader.GetValue(4).ToString();
                string pro_id = myReader.GetValue(5).ToString();
                string pro_name = myReader.GetValue(6).ToString();
                string pro_unit = myReader.GetValue(7).ToString();
                string pro_kg_unit = myReader.GetValue(8).ToString();
                string pro_price_kg = myReader.GetValue(9).ToString();
                string place_id = myReader.GetValue(10).ToString();
                string place_name = myReader.GetValue(11).ToString();
                string weight_in = myReader.GetValue(12).ToString();
                string datetime_in = myReader.GetValue(13).ToString();
                string user_in = myReader.GetValue(14).ToString();
                string weight_out = myReader.GetValue(15).ToString();
                string datetime_out = myReader.GetValue(16).ToString();
                string user_out = myReader.GetValue(17).ToString();
                string weight_net = myReader.GetValue(18).ToString();
                string weight_tare = myReader.GetValue(19).ToString();
                string weight_total = myReader.GetValue(20).ToString();
                string price = myReader.GetValue(21).ToString();
                string price_total = myReader.GetValue(22).ToString();
                string type_id = myReader.GetValue(23).ToString();
                string type_name = myReader.GetValue(24).ToString();
                string weight_desc = myReader.GetValue(25).ToString();
                string evaluation = myReader.GetValue(26).ToString();
                string lan_id = myReader.GetValue(27).ToString();
                string lan_name = myReader.GetValue(28).ToString();
                string suan_id = myReader.GetValue(29).ToString();
                string suan_name = myReader.GetValue(30).ToString();
                string receive_id = myReader.GetValue(31).ToString();
                string bill_no = myReader.GetValue(32).ToString();
                string percent_tare = myReader.GetValue(33).ToString();
                string percent_vat = myReader.GetValue(34).ToString();
                string group_stock = myReader.GetValue(35).ToString();
                string PicPath = myReader.GetValue(36).ToString();
                //data1[] = data;
                UtilityApk utility_apk = new UtilityApk();
                datetime_in = utility_apk.convertToDatetimeString(datetime_in);
                if (!String.IsNullOrEmpty(datetime_out))
                {
                    datetime_out = utility_apk.convertToDatetimeString(datetime_out);
                }else
                {
                    datetime_out = "0000-00-00 00:00:00";
                }

                Console.WriteLine(string.Join(",", datetime_in));
                Console.WriteLine("{0}", datetime_out);
                /*
                WebRequest request = WebRequest.Create("http://afm.revocloudserver.com/api/transaction/max-number");
                WebResponse response = request.GetResponse();
                Stream datahtml = response.GetResponseStream();
                string html = String.Empty;
                using (StreamReader sr = new StreamReader(datahtml))
                {
                    html = sr.ReadToEnd();
                }
                Console.WriteLine("{0}", html);
                */
                Server get_server = new Server();
                get_server.addData(saw_id, weight_no, sequence, car_register, cus_id, cus_name, pro_id, pro_name, pro_unit, pro_kg_unit, pro_price_kg, place_id, place_name, weight_in, datetime_in, user_in, weight_out, datetime_out, user_out, weight_net, weight_tare, weight_total, price, price_total, type_id, type_name, weight_desc, evaluation, lan_id, lan_name, suan_id, suan_name, receive_id, bill_no, percent_tare, percent_vat, group_stock, PicPath);
            }//while
            }//if
            else
            {

            }
            //Console.WriteLine("{0}", data1);
            //Console.WriteLine(string.Join(",", data1));
        }//getAccess



    }//class
}//namespace
