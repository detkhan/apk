using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.IO;

namespace APK
{
    class UtilityApk
    {
        string saw_id;
        string file_access;
        public string convertToDatetimeString(string datetime)
        {
            string datetimeString = "";
            Console.WriteLine("{0}", datetime);
            DateTime dateValue = DateTime.Parse(datetime);
            datetimeString = dateValue.ToString("yyyy-MM-dd HH:mm:ss");
            return datetimeString;
        }

        public string getSawId()
        {
            
            if (File.Exists(@"C:\config.ini"))
            {
                string[] configtxt = File.ReadAllLines(@"C:\config.ini");
                Console.WriteLine(string.Join(",", configtxt[1]));
                string[] tokens = configtxt[1].Split('=');
                saw_id = tokens[1].Trim();
                Console.WriteLine(saw_id);
            }
            return saw_id;
        }

        public string getAccess()
        {

            if (File.Exists(@"C:\config.ini"))
            {
                string[] configtxt = File.ReadAllLines(@"C:\config.ini");
                Console.WriteLine(string.Join(",", configtxt[0]));
                string[] tokens = configtxt[0].Split('=');
                file_access = tokens[1].Trim();
                Console.WriteLine(file_access);
            }
            return file_access;
        }
    }//class
}//namespace
