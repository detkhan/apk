using System;
namespace Connecting
{
    /*
      ;DBTYPE = "ACCESS"
       DBTYPE = "MSSQL"
       DBUSER = ""
       DBPASSWORD = ""
       DBDATABASEPATH = ""
      */
    class connectString
    {
        string DB, UID, UPASSWORD, DBPATH;
        string cmdSTR;
        public connectString(string database, string username, string password, string path)
        {
            if (database.Length > 0 || database != null) DB = database.After("=").Trim(); else DB = "";
            if (username.Length > 0 || username != null) UID = username.After("=").Trim(); else UID = "";
            if (password.Length > 0 || password != null) UPASSWORD = password.After("=").Trim(); else UPASSWORD = "";
            if (path.Length > 0 || path != null) DBPATH = path.After("=").Trim(); else DBPATH = "";

            if (DB.Length > 0)
            {
                if (DB.Contains("ACCESS"))
                {
                    Console.WriteLine(DBPATH);
                   /*cmdSTR = "Provider = Microsoft.Jet.OLEDB.4.0; Data Source =" + DBPATH + ";"
                 + "User Id = " + UID + ";" + "Password = " + UPASSWORD;
                 */
                    cmdSTR = "Provider=Microsoft.Jet.OLEDB.4.0; Data Source=C:/DataExport.mdb";
                }
                else
                {
                    //cmd aql server (MSSQL)
                }
            }
        }

        public string cmdConnectString
        {
            get
            {
                return cmdSTR;
            }
        }

        public string databaseName
        {
            get
            {
                return DB;
            }
        }
    }
}