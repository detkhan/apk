using MySql.Data.MySqlClient;
using System;
using System.Data;
using System.Data.OleDb;
using System.IO;

namespace Connecting
{
    class woodPieces
    {
        public void connAccessToWoodPiecesDatabase()
        {
            DateTime currentDate = DateTime.Today;
            string setCurrentDate = string.Format(currentDate.ToString("yyyy-MM-dd"));
            Console.WriteLine("datetime  {0}", setCurrentDate);
            string servername = "Server = 27.254.36.4;";
            string database = "Database = revoclou_afm;";
            string username = "Uid = revoclou_afm;";
            string password = "Pwd = ?AL2cP4l#o?X;";
            string ConnMySqlDatabase = servername + database + username + password + "CharSet=utf8;";
            MySqlConnection mysqlconn = new MySqlConnection(ConnMySqlDatabase);
            using (mysqlconn)
            {
                //query for check if have data in mysql 
                mysqlconn.Open();
                MySqlCommand cmd = mysqlconn.CreateCommand();
                cmd.CommandText = string.Format("SELECT date(`datetime`) from wood_pieces where date(datetime) = '{0}'", setCurrentDate);
                MySqlDataReader reader = cmd.ExecuteReader();
                //access database
                connectString connSTR;
                if (File.Exists(@"C:\config.ini"))
                {
                    string[] configtxt = File.ReadAllLines(@"C:\config.ini");
                    //Console.WriteLine("found path:{0}", filename);
                    if (configtxt[0].Contains(";DBTYPE = \"ACCESS\""))
                    {
                        connSTR = new connectString(configtxt[1], configtxt[2], configtxt[3], configtxt[4]);
                    }
                    else
                    {
                        connSTR = new connectString(configtxt[0], configtxt[2], configtxt[3], configtxt[4]);
                    }
                    string sawId = configtxt[5].After("=").Trim();
                    string databasePath = configtxt[4].After("=").Trim();
                    string DirPath = System.IO.Path.GetDirectoryName(System.Reflection.Assembly.GetExecutingAssembly().Location);
                    if (File.Exists(databasePath))
                    {
                        if (connSTR.databaseName.Contains("ACCESS"))
                        {
                            //wood_pieces_income
                            string connectSTR = connSTR.cmdConnectString;
                            OleDbConnection accessConn = new OleDbConnection(connectSTR);
                            accessConn.Open();
                            string incomingQuery = string.Format("SELECT SUM(`weight_total`) as wood_pieces_income from tbl_weight where pro_id = '001' and type_name = 'รับเชื้อเพลิง' and DateValue(datetime_out) = '{0}'", convertDateToAccessFormat(setCurrentDate));
                            OleDbCommand accessCommand = new OleDbCommand(incomingQuery, accessConn);
                            using (OleDbDataReader rdr = accessCommand.ExecuteReader())
                            {
                                //iterate through the reader here
                                while (rdr.Read())
                                {
                                    //or reader[columnName] for each column name
                                    //if have data just update in mysql or it have not data just insert in mysql
                                    using (mysqlconn)
                                    {
                                        MySqlCommand command = mysqlconn.CreateCommand();
                                        if (reader.Read() == true)
                                        {
                                            //updated
                                            string data = string.Format("{0}", rdr[0]);
                                            Console.WriteLine("check data : {0}", data);
                                            if (!string.IsNullOrEmpty(data))
                                            {
                                                command.CommandText = string.Format("UPDATE wood_pieces SET `wood_pieces_incoming` = {0} ,updated_by = 'connecting program updated' where date(datetime) = '{1}'", rdr[0], setCurrentDate);
                                            }
                                            else
                                            {
                                                command.CommandText = string.Format("UPDATE wood_pieces SET `wood_pieces_incoming` = {0} ,updated_by = 'connecting program updated' where date(datetime) = '{1}'", "0", setCurrentDate);
                                            }
                                            Console.WriteLine("update data wood_pieces_incoming : " + rdr[0]);
                                            reader.Close();
                                            command.ExecuteNonQuery();
                                        }
                                        else
                                        {
                                            //insert
                                            string data = string.Format("{0}", rdr[0]);
                                            if (!string.IsNullOrEmpty(data))
                                            {
                                                command.CommandText = "INSERT INTO wood_pieces(`sawId`,`wood_pieces_incoming`,`datetime`,created_by,updated_by) VALUES ('" + sawId + "','" + rdr[0] + "','" + convertToDatetimeString(string.Format("{0}", setCurrentDate)) + "','connecting program created','connecting program created')";
                                            }
                                            else
                                            {
                                                command.CommandText = "INSERT INTO wood_pieces(`sawId`,`wood_pieces_incoming`,`datetime`,created_by,updated_by) VALUES ('" + sawId + "','" + "0" + "','" + convertToDatetimeString(string.Format("{0}", setCurrentDate)) + "','connecting program created','connecting program created')";
                                            }
                                            reader.Close();
                                            command.ExecuteNonQuery();
                                        }
                                        if (mysqlconn.State == ConnectionState.Open)
                                        {
                                            mysqlconn.Close();
                                        }
                                    }
                                }
                                rdr.Close();
                            }
                            accessConn.Close();
                        }
                    }
                }
            }
        }

        public void connAccessToWoodPiecesOutcomeDatabase()
        {
            DateTime currentDate = DateTime.Today;
            string setCurrentDate = string.Format(currentDate.ToString("yyyy-MM-dd"));
            Console.WriteLine("datetime  {0}", setCurrentDate);
            string servername = "Server = 27.254.36.4;";
            string database = "Database = revoclou_afm;";
            string username = "Uid = revoclou_afm;";
            string password = "Pwd = ?AL2cP4l#o?X;";
            string ConnMySqlDatabase = servername + database + username + password + "CharSet=utf8;";
            MySqlConnection mysqlconn = new MySqlConnection(ConnMySqlDatabase);
            using (mysqlconn)
            {
                //query for check if have data in mysql 
                mysqlconn.Open();
                MySqlCommand cmd = mysqlconn.CreateCommand();
                cmd.CommandText = string.Format("SELECT date(`datetime`) from wood_pieces where date(datetime) = '{0}'", setCurrentDate);
                MySqlDataReader reader = cmd.ExecuteReader();
                //access database
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
                    string databasePath = configtxt[4].After("=").Trim();
                    if (File.Exists(databasePath))
                    {
                        if (connSTR.databaseName.Contains("ACCESS"))
                        {
                            //wood_pieces_income
                            string connectSTR = connSTR.cmdConnectString;
                            OleDbConnection accessConn = new OleDbConnection(connectSTR);
                            accessConn.Open();
                            string incomingQuery = string.Format("SELECT SUM(`weight_total`) as wood_pieces_income from tbl_weight where pro_id = '001' and type_name = 'เบิกเชื้อเพลิง' and DateValue(datetime_out) = '{0}'", convertDateToAccessFormat(setCurrentDate));//string.Format("SELECT SUM(`weight_total`) as weight_total from tbl_weight where datetime_out = '{0}'",a);
                            OleDbCommand accessCommand = new OleDbCommand(incomingQuery, accessConn);
                            using (OleDbDataReader rdr = accessCommand.ExecuteReader())
                            {
                                //iterate through the reader here
                                while (rdr.Read())
                                {
                                    //or reader[columnName] for each column name
                                    //if have data just update in mysql or it have not data just insert in mysql
                                    using (mysqlconn)
                                    {
                                        MySqlCommand command = mysqlconn.CreateCommand();
                                        if (reader.Read() == true)
                                        {
                                            //updated
                                            string data = string.Format("{0}", rdr[0]);
                                            if (!string.IsNullOrEmpty(data))
                                            {
                                                command.CommandText = string.Format("UPDATE wood_pieces SET `wood_sale` = {0} ,updated_by = 'connecting program updated' where date(datetime) = '{1}'", rdr[0], setCurrentDate);
                                            }
                                            else
                                            {
                                                command.CommandText = string.Format("UPDATE wood_pieces SET `wood_sale` = {0} ,updated_by = 'connecting program updated' where date(datetime) = '{1}'", "0", setCurrentDate);
                                            }
                                            Console.WriteLine("update data wood_pieces_outcoming : " + rdr[0]);
                                            reader.Close();
                                            command.ExecuteNonQuery();
                                        }
                                        else
                                        {
                                            string data = string.Format("{0}", rdr[0]);
                                            if (!string.IsNullOrEmpty(data))
                                            {
                                                command.CommandText = "INSERT INTO wood_pieces(`sawId`,`wood_sale`,`datetime`,created_by,updated_by) VALUES ('" + sawId + "','" + rdr[0] + "','" + convertToDatetimeString(string.Format("{0}", setCurrentDate)) + "','connecting program created','connecting program created')";
                                            }
                                            else
                                            {
                                                command.CommandText = "INSERT INTO wood_pieces(`sawId`,`wood_sale`,`datetime`,created_by,updated_by) VALUES ('" + sawId + "','" + "0" + "','" + convertToDatetimeString(string.Format("{0}", setCurrentDate)) + "','connecting program created','connecting program created')";
                                            }
                                            //insert
                                            reader.Close();
                                            command.ExecuteNonQuery();
                                        }
                                        if (mysqlconn.State == ConnectionState.Open)
                                        {
                                            mysqlconn.Close();
                                        }
                                    }
                                }
                                rdr.Close();
                            }
                            accessConn.Close();
                        }
                    }
                }
            }
        }

        private string convertToDatetimeString(string datetime)
        {
            string datetimeString = "";
            DateTime dateValue = DateTime.Parse(datetime);
            datetimeString = dateValue.ToString("yyyy-MM-dd HH:mm:ss");
            return datetimeString;
        }

        private string convertDateToAccessFormat(string date)
        {
            string dateString = "";
            DateTime dateValue = DateTime.Parse(date);
            dateString = dateValue.ToString("M/d/yyyy");
            return dateString;
        }
    }
}
