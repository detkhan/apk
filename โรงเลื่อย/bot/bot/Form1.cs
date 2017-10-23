using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;
using System.Data.OleDb;

namespace bot
{
    public partial class Form1 : Form
    {
        public Form1()
        {
            InitializeComponent();
            string conStr = "Provider=Microsoft.Jet.OLEDB.4.0; Data Source=C:/DataExport.mdb";
            OleDbConnection conn = new OleDbConnection(conStr);
            conn.Open();
            OleDbCommand myCommand = conn.CreateCommand();
            myCommand.CommandText = "SELECT * FROM  tbl_weight";
            OleDbDataReader myReader = myCommand.ExecuteReader();

            dataGridView1.ColumnCount = 3;
            dataGridView1.Columns[0].HeaderText = "ID";
            dataGridView1.Columns[1].HeaderText = "Name";
            dataGridView1.Columns[2].HeaderText = "Weight No";


            while (myReader.Read())
            {
                // Console.WriteLine("{0}", myReader.GetInt32(0));
                //label2.Text = "" + myReader.GetString(4);
                String[] data ={
                    myReader.GetValue(0).ToString(),
                    myReader.GetValue(4).ToString(),
                    myReader.GetValue(3).ToString()
                };
                dataGridView1.Rows.Add(data);

            }

            // 
            conn.Close();

        }

        private void Form1_Load(object sender,EventArgs e)
        {



        }
    }
}
