using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;
using System.Net;
using Newtonsoft.Json.Linq;
using System.IO;

namespace APK
{
    public partial class Form1 : Form
    {
        int number = 0;
        public Form1()
        {
        InitializeComponent();
        appStart();
        InitTimer();
        }
        private Timer timer1;
        private void InitTimer()
        {
            timer1 = new Timer();
            timer1.Tick += new EventHandler(timer1_Tick);
            timer1.Interval = 60000; // in miliseconds
            timer1.Start();
        }

        private void timer1_Tick(object sender, EventArgs e)
        {
            number++;
            appStart();
        }

        public  void appStart()
        {
        this.label2.Text = string.Format("กำลังอับเดตข้อมูล");
        Server getjson = new Server();
        UtilityApk utilityApk = new UtilityApk();
        Access getAccessDb = new Access();
        string saw_id = utilityApk.getSawId();
        Console.WriteLine(saw_id);
        string weight_no = getjson.getLastId(saw_id);
        Console.WriteLine(weight_no);
        getAccessDb.getAccess(weight_no, saw_id);
            this.label1.Text = string.Format(""+number);
            this.label1.Update();
            this.label2.Text = string.Format("อับเดตข้อมูลถึงล่าสุดแล้ว");
            this.label2.Update();

        }


        //disable close button on window form
        private const int CP_NOCLOSE_BUTTON = 0x200;
        protected override CreateParams CreateParams
        {
            get
            {
                CreateParams myCp = base.CreateParams;
                myCp.ClassStyle = myCp.ClassStyle | CP_NOCLOSE_BUTTON;
                return myCp;
            }
        }


        //hide program when window minimized
        private void Form1_Resize(object sender, EventArgs e)
        {
            if (WindowState == FormWindowState.Minimized)
            {
                Hide();
                notifyIcon1.Icon = SystemIcons.Application;
                notifyIcon1.Visible = true;
            }
        }
        //click program on notifyicon taskbar
        private void notifyIcon1_mouseOneClicked(object sender, MouseEventArgs e)
        {
            Show();
            WindowState = FormWindowState.Normal;
            notifyIcon1.Visible = false;
        }


    }
}
