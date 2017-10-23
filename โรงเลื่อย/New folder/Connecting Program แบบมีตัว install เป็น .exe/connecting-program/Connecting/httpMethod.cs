using Newtonsoft.Json.Linq;
using System;
using System.IO;
using System.Net;
using System.Text;

namespace Connecting
{
    class httpMethod
    {
        public string postUploadImage(string weight_no, string fileImage)
        {
            string[] configtxt = File.ReadAllLines(@"C:\config.ini");
            string sawId = configtxt[5].After("=").Trim();
            HttpWebRequest request = (HttpWebRequest)WebRequest.Create("http://afm.revocloudserver.com/api/utility/upload-image");
            request.ContentType = "application/json; charset=utf-8";
            request.Method = "POST";
            transactionLog tranLog = new transactionLog();
            string image = null;
            using (var streamWriter = new StreamWriter(request.GetRequestStream()))
            {
                var bytes = Encoding.Default.GetBytes(fileImage);
                var base64 = Convert.ToBase64String(bytes);
                string json = "{\"imagePath\":\"" + base64 + "\"}";
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
                    tranLog.insertTransactionLog(weight_no, sawId, "upload", "success", replaceString);
                }
                else
                {
                    var message = (string)obj[0][0]["data"]["message"];
                    tranLog.insertTransactionLog(weight_no, sawId, "upload", "failed", message);
                }
            }
            return image;
        }
        public string postCheckTransaction(string dataNumber)
        {
            HttpWebRequest request = (HttpWebRequest)WebRequest.Create("http://afm.revocloudserver.com/api/utility/check-transaction");
            request.ContentType = "application/json; charset=utf-8";
            request.Method = "POST";
            string statusSTR = null;
            using (var streamWriter = new StreamWriter(request.GetRequestStream()))
            {
                string json = "{\"countNumber\":\"" + dataNumber + "\"}";
                streamWriter.Write(json);
                streamWriter.Flush();
                streamWriter.Close();
            }
            var httpResponse = (HttpWebResponse)request.GetResponse();
            using (var streamReader = new StreamReader(httpResponse.GetResponseStream()))
            {
                var result = streamReader.ReadToEnd();
                var obj = JArray.Parse(result);
                var status = (string)obj[0]["data"]["status"];
                if (status.Equals("true"))
                {
                    statusSTR = "true";
                }
                else
                {
                    statusSTR = "failed";
                }
            }
            return statusSTR;
        }
    }
}
