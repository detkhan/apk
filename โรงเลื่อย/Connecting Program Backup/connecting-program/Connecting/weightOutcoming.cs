using MySql.Data.MySqlClient;
using System;
using System.Data;
using System.Data.OleDb;
using System.IO;

namespace Connecting
{
    class weightOutcoming
    {
        public void connAccessToWeightOutcomingDatabase()
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
                mysqlconn.Open();
                MySqlCommand cmd = mysqlconn.CreateCommand();
                cmd.CommandText = string.Format("SELECT date(`datetime`) from weight_outcoming where date(datetime) = '{0}'", setCurrentDate);
                MySqlDataReader reader = cmd.ExecuteReader();

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
                            string connectSTR = connSTR.cmdConnectString;
                            OleDbConnection accessConn = new OleDbConnection(connectSTR);
                            accessConn.Open();
                            string queryCommand = string.Format("SELECT SUM(`weight_total`) as weight_total from tbl_weight where pro_id = '005' and DateValue(datetime_out) = '{0}'", convertDateToAccessFormat(setCurrentDate));//string.Format("SELECT SUM(`weight_total`) as weight_total from tbl_weight where datetime_out = '{0}'",a);
                            OleDbCommand accessCommand = new OleDbCommand(queryCommand, accessConn);
                            using (OleDbDataReader rdr = accessCommand.ExecuteReader())
                            {
                                //iterate through the reader here
                                while (rdr.Read())
                                {
                                    using (mysqlconn)
                                    {
                                        //or reader[columnName] for each column name
                                        //if have data just update in mysql or it have not data just insert in mysql
                                        MySqlCommand command = mysqlconn.CreateCommand();
                                        if (reader.Read() == true)
                                        {
                                            //updated
                                            string data = string.Format("{0}", rdr[0]);
                                            if (!string.IsNullOrEmpty(data))
                                            {
                                                command.CommandText = string.Format("UPDATE weight_outcoming SET `wood_grades_weight` = {0} ,updated_by = 'connecting program updated' where date(datetime) = '{1}'", rdr[0], setCurrentDate);
                                            }
                                            else
                                            {
                                                command.CommandText = string.Format("UPDATE weight_outcoming SET `wood_grades_weight` = {0} ,updated_by = 'connecting program updated' where date(datetime) = '{1}'", "0", setCurrentDate);
                                            }
                                            Console.WriteLine("update data : " + rdr[0]);
                                            reader.Close();
                                            command.ExecuteNonQuery();
                                        }
                                        else
                                        {
                                            //inserted
                                            string data = string.Format("{0}", rdr[0]);
                                            if (!string.IsNullOrEmpty(data))
                                            {
                                                command.CommandText = "INSERT INTO weight_outcoming(`sawId`,`wood_grades_weight`,`datetime`,created_by,updated_by) VALUES ('" + sawId + "','" + rdr[0] + "','" + convertToDatetimeString(string.Format("{0}", setCurrentDate)) + "','connecting program created','connecting program created')";
                                            }
                                            else
                                            {
                                                command.CommandText = "INSERT INTO weight_outcoming(`sawId`,`wood_grades_weight`,`datetime`,created_by,updated_by) VALUES ('" + sawId + "','" + "0" + "','" + convertToDatetimeString(string.Format("{0}", setCurrentDate)) + "','connecting program created','connecting program created')";
                                            }
                                            Console.WriteLine("insert data : " + rdr[0]);
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

        public void connAccessToWeightOutcomingSlabDatabase()
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
                mysqlconn.Open();
                MySqlCommand cmd = mysqlconn.CreateCommand();
                cmd.CommandText = string.Format("SELECT date(`datetime`) from weight_outcoming where date(datetime) = '{0}'", setCurrentDate);
                MySqlDataReader reader = cmd.ExecuteReader();

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
                            string connectSTR = connSTR.cmdConnectString;
                            OleDbConnection accessConn = new OleDbConnection(connectSTR);
                            accessConn.Open();
                            string queryCommand = string.Format("SELECT SUM(`weight_total`) as weight_total from tbl_weight where pro_id = '004' and DateValue(datetime_out) = '{0}'", convertDateToAccessFormat(setCurrentDate));//string.Format("SELECT SUM(`weight_total`) as weight_total from tbl_weight where datetime_out = '{0}'",a);
                            OleDbCommand accessCommand = new OleDbCommand(queryCommand, accessConn);
                            using (OleDbDataReader rdr = accessCommand.ExecuteReader())
                            {
                                //iterate through the reader here
                                while (rdr.Read())
                                {
                                    using (mysqlconn)
                                    {
                                        //or reader[columnName] for each column name
                                        //if have data just update in mysql or it have not data just insert in mysql
                                        MySqlCommand command = mysqlconn.CreateCommand();
                                        if (reader.Read() == true)
                                        {
                                            //updated
                                            string data = string.Format("{0}", rdr[0]);
                                            if (!string.IsNullOrEmpty(data))
                                            {
                                                command.CommandText = string.Format("UPDATE weight_outcoming SET `slab_weight` = {0} ,updated_by = 'connecting program updated' where date(datetime) = '{1}'", rdr[0], setCurrentDate);
                                            }
                                            else
                                            {
                                                command.CommandText = string.Format("UPDATE weight_outcoming SET `slab_weight` = {0} ,updated_by = 'connecting program updated' where date(datetime) = '{1}'", "0", setCurrentDate);
                                            }
                                            Console.WriteLine("update data : " + rdr[0]);
                                            reader.Close();
                                            command.ExecuteNonQuery();
                                        }
                                        else
                                        {
                                            //inserted
                                            string data = string.Format("{0}", rdr[0]);
                                            if (!string.IsNullOrEmpty(data))
                                            {
                                                command.CommandText = "INSERT INTO weight_outcoming(`sawId`,`slab_weight`,`datetime`,created_by,updated_by) VALUES ('" + sawId + "','" + rdr[0] + "','" + convertToDatetimeString(string.Format("{0}", setCurrentDate)) + "','connecting program created','connecting program created')";
                                            }
                                            else
                                            {
                                                command.CommandText = "INSERT INTO weight_outcoming(`sawId`,`slab_weight`,`datetime`,created_by,updated_by) VALUES ('" + sawId + "','" + "0" + "','" + convertToDatetimeString(string.Format("{0}", setCurrentDate)) + "','connecting program created','connecting program created')";
                                            }
                                            Console.WriteLine("insert data : " + rdr[0]);
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

        public void connAccessToWeightOutcomingSawdustDatabase()
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
                mysqlconn.Open();
                MySqlCommand cmd = mysqlconn.CreateCommand();
                cmd.CommandText = string.Format("SELECT date(`datetime`) from weight_outcoming where date(datetime) = '{0}'", setCurrentDate);
                MySqlDataReader reader = cmd.ExecuteReader();

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
                            string connectSTR = connSTR.cmdConnectString;
                            OleDbConnection accessConn = new OleDbConnection(connectSTR);
                            accessConn.Open();
                            string queryCommand = string.Format("SELECT SUM(`weight_total`) as weight_total from tbl_weight where pro_id = '003' and DateValue(datetime_out) = '{0}'", convertDateToAccessFormat(setCurrentDate));//string.Format("SELECT SUM(`weight_total`) as weight_total from tbl_weight where datetime_out = '{0}'",a);
                            OleDbCommand accessCommand = new OleDbCommand(queryCommand, accessConn);
                            using (OleDbDataReader rdr = accessCommand.ExecuteReader())
                            {
                                //iterate through the reader here
                                while (rdr.Read())
                                {
                                    using (mysqlconn)
                                    {
                                        //or reader[columnName] for each column name
                                        //if have data just update in mysql or it have not data just insert in mysql
                                        MySqlCommand command = mysqlconn.CreateCommand();
                                        if (reader.Read() == true)
                                        {
                                            //updated
                                            string data = string.Format("{0}", rdr[0]);
                                            if (!string.IsNullOrEmpty(data))
                                            {
                                                command.CommandText = string.Format("UPDATE weight_outcoming SET `sawdust_weight` = {0} ,updated_by = 'connecting program updated' where date(datetime) = '{1}'", rdr[0], setCurrentDate);
                                            }
                                            else
                                            {
                                                command.CommandText = string.Format("UPDATE weight_outcoming SET `sawdust_weight` = {0} ,updated_by = 'connecting program updated' where date(datetime) = '{1}'", "0", setCurrentDate);
                                            }
                                            Console.WriteLine("update data : " + rdr[0]);
                                            reader.Close();
                                            command.ExecuteNonQuery();
                                        }
                                        else
                                        {
                                            //inserted
                                            string data = string.Format("{0}", rdr[0]);
                                            if (!string.IsNullOrEmpty(data))
                                            {
                                                command.CommandText = "INSERT INTO weight_outcoming(`sawId`,`sawdust_weight`,`datetime`,created_by,updated_by) VALUES ('" + sawId + "','" + rdr[0] + "','" + convertToDatetimeString(string.Format("{0}", setCurrentDate)) + "','connecting program created','connecting program created')";
                                            }
                                            else
                                            {
                                                command.CommandText = "INSERT INTO weight_outcoming(`sawId`,`sawdust_weight`,`datetime`,created_by,updated_by) VALUES ('" + sawId + "','" + "0" + "','" + convertToDatetimeString(string.Format("{0}", setCurrentDate)) + "','connecting program created','connecting program created')";
                                            }
                                            Console.WriteLine("insert data : " + rdr[0]);
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

