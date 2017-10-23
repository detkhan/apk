using MySql.Data.MySqlClient;
using System;
using System.Data;

namespace Connecting
{
    class connectionLog
    {
        public void insertConnectionLog(string sawmillname,string from, string status, string message)
        {
            string servername = "Server = 27.254.36.4;";
            string database = "Database = revoclou_afm;";
            string username = "Uid = revoclou_afm;";
            string password = "Pwd = ?AL2cP4l#o?X;";

            string ConnMySqlDatabase = servername + database + username + password + "CharSet=utf8;";

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
                cmd.CommandText = "INSERT INTO connecting_log(sawmill_name,`from`,`status`,message) VALUES ('" + sawmillname +"','"+ from +"','"+ status +"','"+ message +"')";
                cmd.ExecuteNonQuery();
                
            }
            catch (Exception ex)
            {
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
