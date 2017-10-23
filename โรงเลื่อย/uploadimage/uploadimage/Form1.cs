using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Net;
using System.Net.Http;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;
using System.IO;
using Newtonsoft.Json.Linq;

namespace uploadimage
{
    public partial class Form1 : Form
    {
        public Form1()
        {
            InitializeComponent();
            string fileImage = @"C:\image.png";
            HttpWebRequest request = (HttpWebRequest)WebRequest.Create("http://afm.revocloudserver.com/api/utility/upload-image");
            request.ContentType = "application/json; charset=utf-8";
            request.Method = "POST";
            if (File.Exists(fileImage))
            {
                Console.WriteLine("have file");
            }
            else
            {
                Console.WriteLine("no file");
            }
                string image = null;
            using (var streamWriter = new StreamWriter(request.GetRequestStream()))
            {
                //var bytes = Encoding.Default.GetBytes(fileImage);
                //var base64 = Convert.ToBase64String(bytes);
                byte[] imageArray = System.IO.File.ReadAllBytes(fileImage);
                string base64 = Convert.ToBase64String(imageArray);
                string json = "{\"imagePath\":\"" + base64 + "\"}";
                Console.WriteLine(base64);
                streamWriter.Write(json);
                streamWriter.Flush();
                streamWriter.Close();
            }
            var httpResponse = (HttpWebResponse)request.GetResponse();
            using (var streamReader = new StreamReader(httpResponse.GetResponseStream()))
            {
                var result = streamReader.ReadToEnd();
                var obj = JArray.Parse(result);
                var status = (string)obj[0][0]["data"]["status"];
                if (status.Equals("true"))
                {
                    image = (string)obj[1][0]["image"];
                    string replaceString = image.Replace('\\', '/');
                    Console.WriteLine(image);
                }
                else
                {
                    var message = (string)obj[0][0]["data"]["message"];
                    Console.WriteLine(message);
                }
            }

        }
    }
}
