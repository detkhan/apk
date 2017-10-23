using MySql.Data.MySqlClient;
using System;
using System.Data;

namespace Connecting
{
    class transaction
    {
        public void insertTransactionData(
            string sawId,
            string productId,
            string truck_register_number,
            string weight_no,
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
            string pic_path,
            string shortname)
        {
            string servername = "Server = 27.254.36.4;";
            string database = "Database = revoclou_afm;";
            string username = "Uid = revoclou_afm;";
            string password = "Pwd = ?AL2cP4l#o?X;";
            string ConnMySqlDatabase = servername + database + username + password + "CharSet=utf8;";
            transactionLog tranLog = new transactionLog();
            httpMethod http = new httpMethod();
            MySqlConnection mysqlconn = null;
            MySqlCommand cmd;
            try
            {
                mysqlconn = new MySqlConnection(ConnMySqlDatabase);
            }
            catch (Exception ex)
            {
                Console.WriteLine("Error can't connect with database. {0}", ex.Message);
                return;
            }
            try
            {
                mysqlconn.Open();
            }
            catch (Exception ex)
            {
                Console.WriteLine("Can't connect with database sql.{0}", ex.Message);
                return;
            }
            try
            {
              
                cmd = mysqlconn.CreateCommand();
                cmd.CommandText = "INSERT INTO transaction_temp(sawId,productId,truck_register_number,weight_no,customer_name_in,datetime_in,weight_in,customer_name_out,datetime_out,weight_out,weight_net,weight_tare,weight_total,product_price_unit,price_total,type_name,pic_path) VALUES ('" 
                    + sawId + "','" 
                    + productId + "','" 
                    + truck_register_number + "','" 
                    + weight_no + "','"
                    + customer + "','"
                    + datetime_in + "','" 
                    + weight_in + "','" 
                    + customer + "','" 
                    + datetime_out + "','" 
                    + weight_out + "','" 
                    + weight_net + "','" 
                    + weight_tare + "','" 
                    + weight_total + "','" 
                    + product_price_unit + "','" 
                    + price_total + "','"
                    + type_name + "','"
                    + pic_path + "')";
                cmd.ExecuteNonQuery();
                tranLog.insertTransactionLog(weight_no, shortname, "insert", "success", "Insert data to transaction_temp");
            }
            catch (Exception ex)
            {
                tranLog.insertTransactionLog(weight_no, shortname, "insert", "failed", string.Format("Can't insert data to database.{0}", ex.Message));
                Console.WriteLine("Can't insert data to database.{0}", ex.Message);
            }
            finally
            {
                if (mysqlconn.State == ConnectionState.Open)
                {
                    mysqlconn.Close();
                }
            }
        }
    }
}
