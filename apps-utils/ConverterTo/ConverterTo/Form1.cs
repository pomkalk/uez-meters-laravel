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
using System.Xml.Linq;


namespace ConverterTo
{
    public partial class Form1 : Form
    {
        Dictionary<string, Street> data = new Dictionary<string, Street>();

        public Form1()
        {
            InitializeComponent();
        }

        private void Form1_Load(object sender, EventArgs e)
        {
            BackgroundWorker bw = new BackgroundWorker();
            bw.DoWork += bw_DoWork2;
            bw.RunWorkerAsync();
        }

        private string q(string query)
        {
            return query.Replace("$addr", "S_ADDR.DBF").Replace("$mt", "S_SC.DBF");
        }


        private void clearProgress()
        {
            this.progressBar1.Invoke((MethodInvoker)delegate {
                this.progressBar1.Value = 0;
            });
        }

        private void setMaxProgress(int max)
        {
            this.progressBar1.Invoke((MethodInvoker)delegate
            {
                this.progressBar1.Maximum = max;
            });
        }
        private void setProgress(int val)
        {
            this.progressBar1.Invoke((MethodInvoker)delegate
            {
                this.progressBar1.Value = val;
            });
        }


        List<string> getService(string name)
        {
            List<string> res = new List<string>();
            switch (name)
            {
                case "Холодная вода":
                    res.Add("1");
                    res.Add("Холодная вода");
                    res.Add("5.01");
                    res.Add("2");
                    break;
                case "Горячая вода":
                    res.Add("2");
                    res.Add("Горячая вода");
                    res.Add("3.37");
                    res.Add("2");
                    break;
                case "Электроэнергия":
                    res.Add("4");
                    res.Add("Электроэнергия");
                    res.Add("0");
                    res.Add("0");
                    break;
                case "Отопление":
                    res.Add("3");
                    res.Add("Отопление");
                    res.Add("0");
                    res.Add("0");
                    break;
            }

            return res;
        }

        void bw_DoWork2(object sender, DoWorkEventArgs e)
        {
            int street_id = 1;
            int building_id = 1;
            int apartment_id = 1;
            int meter_id = 1;

            OleDbConnection con = new OleDbConnection("Provider=Microsoft.Jet.OLEDB.4.0;Data Source=.;Extended Properties=dBASE IV;User ID=Admin;Password=;");
            OleDbDataAdapter da = new OleDbDataAdapter(q("SELECT a.CK, a.NAM, a.VID, a.DOM, a.KRP, a.KVR, a.SKV , s.ID, s.USL, s.STATUS, s.DTY, s.DTM, s.VAL, s.JL, a.OP FROM $addr as a LEFT JOIN $mt as s ON (a.CK = s.CK) ORDER BY a.NAM, a.VID, a.DOM, a.KRP, a.KVR, a.SKV"), con);
            DataTable lines = new DataTable();
            da.Fill(lines);

            this.setMaxProgress(lines.Rows.Count);
            for (int i = 0; i < lines.Rows.Count; i++)
            {
                this.setProgress(i + 1);
                string ls = lines.Rows[i][0].ToString();
                string street = lines.Rows[i][1].ToString();
                string prefix = lines.Rows[i][2].ToString();
                string dom = lines.Rows[i][3].ToString();
                string housing = lines.Rows[i][4].ToString().ToLower();
                string number = lines.Rows[i][5].ToString();
                string lit = lines.Rows[i][6].ToString();
                string mid = lines.Rows[i][7].ToString();
                string usl = lines.Rows[i][8].ToString();
                string status = lines.Rows[i][9].ToString();
                string dty = lines.Rows[i][10].ToString();
                string dtm = lines.Rows[i][11].ToString();
                string val = lines.Rows[i][12].ToString();
                string men = lines.Rows[i][13].ToString();
                string space = lines.Rows[i][14].ToString();



                if (data.ContainsKey(street+"-"+prefix))
                {

                    if (data[street + "-" + prefix].buildings.ContainsKey(dom + "-" + housing))
                    {
                        if (data[street + "-" + prefix].buildings[dom + "-" + housing].apartments.ContainsKey(ls))
                        {
                            if (data[street + "-" + prefix].buildings[dom + "-" + housing].apartments[ls].meters.ContainsKey(mid))
                            {
                                MessageBox.Show("CONTAINING");
                            }
                            else
                            {
                                if (mid != "")
                                {
                                    Meter mt = new Meter(meter_id++, mid, usl, status, dty, dtm, val);
                                    data[street + "-" + prefix].buildings[dom + "-" + housing].apartments[ls].meters.Add(mid, mt);
                                }
                            }
                        }
                        else
                        {
                            Apartment ap = new Apartment(apartment_id++, number, lit, men, ls, space);
                            data[street + "-" + prefix].buildings[dom + "-" + housing].apartments.Add(ls, ap);

                            if (mid != "")
                            {
                                Meter mt = new Meter(meter_id++, mid, usl, status, dty, dtm, val);
                                data[street + "-" + prefix].buildings[dom + "-" + housing].apartments[ls].meters.Add(mid, mt);
                            }
                        }
                    }
                    else
                    {
                        Building b = new Building(building_id++, dom, housing);
                        data[street + "-" + prefix].buildings.Add(dom + "-" + housing, b);

                        Apartment ap = new Apartment(apartment_id++, number, lit, men, ls, space);
                        data[street + "-" + prefix].buildings[dom + "-" + housing].apartments.Add(ls, ap);

                        if (mid != "")
                        {
                            Meter mt = new Meter(meter_id++, mid, usl, status, dty, dtm, val);
                            data[street + "-" + prefix].buildings[dom + "-" + housing].apartments[ls].meters.Add(mid, mt);
                        }
                    }
                    
                }
                else
                {
                    Street s = new Street(street_id++, street, prefix);
                    data.Add(street + "-" + prefix, s);


                    Building b = new Building(building_id++, dom, housing);
                    data[street + "-" + prefix].buildings.Add(dom + "-" + housing, b);

                    Apartment ap = new Apartment(apartment_id++, number, lit, men, ls, space);
                    data[street + "-" + prefix].buildings[dom + "-" + housing].apartments.Add(ls, ap);

                    if (mid != "")
                    {
                        Meter mt = new Meter(meter_id++, mid, usl, status, dty, dtm, val);
                        data[street + "-" + prefix].buildings[dom + "-" + housing].apartments[ls].meters.Add(mid, mt);
                    }
                    
                }
            }

            //
            XDocument doc = new XDocument();
            XElement root = new XElement("meters");

            doc.Add(root);
            XElement x_data = new XElement("data");
            root.Add(x_data);
            foreach (string line in data.Keys)
            {
                Street st = data[line];
                foreach (string line2 in st.buildings.Keys)
                {
                    Building bg = st.buildings[line2];
                    foreach(string line3 in bg.apartments.Keys)
                    {
                        Apartment ap = bg.apartments[line3];
                        foreach (string line4 in ap.meters.Keys)
                        {
                            Meter mtr = ap.meters[line4];
                            ap.xml.Add(mtr.xml);
                        }
                        bg.xml.Add(ap.xml);
                    }
                    st.xml.Add(bg.xml);
                }
                x_data.Add(st.xml);
            }

            da = new OleDbDataAdapter(q("SELECT DISTINCT s.USL FROM $mt as s"), con);
            DataTable services = new DataTable();
            da.Fill(services);

            XElement srv = new XElement("services");
            for (int i = 0; i < services.Rows.Count; i++)
            {
                XElement service = new XElement("service", 
                    new XAttribute("id", getService(services.Rows[i][0].ToString())[0]), 
                    new XAttribute("name", services.Rows[i][0].ToString()),
                    new XAttribute("norm", getService(services.Rows[i][0].ToString())[2]), 
                    new XAttribute("additional", getService(services.Rows[i][0].ToString())[3]) 

                    );
                
                srv.Add(service);
            }

            root.Add(srv);

            doc.Save(DateTime.Now.ToShortDateString() + ".xml");
        }

        void bw_DoWork(object sender, DoWorkEventArgs e)
        {
            

            XDocument doc = new XDocument();
            XElement root = new XElement("meters");
            doc.Add(root);

            OleDbConnection con = new OleDbConnection("Provider=Microsoft.Jet.OLEDB.4.0;Data Source=.;Extended Properties=dBASE IV;User ID=Admin;Password=;");

            OleDbDataAdapter da = new OleDbDataAdapter(q("SELECT DISTINCT a.NAM, a.VID FROM $addr as a ORDER BY a.NAM, a.VID"), con);
            DataTable streets = new DataTable();
            da.Fill(streets);

            XElement data = new XElement("data");
            root.Add(data);

            this.clearProgress();
            this.setMaxProgress(streets.Rows.Count);
            
            for (int i = 0; i < streets.Rows.Count; i++)
            {
                this.setProgress(i);

                string street = streets.Rows[i][0].ToString();
                string prefix = streets.Rows[i][1].ToString();


                XElement x_street = new XElement("street", new XAttribute("name", street), new XAttribute("prefix",prefix));
                data.Add(x_street);

                da = new OleDbDataAdapter(q("SELECT DISTINCT a.DOM, a.KRP FROM $addr as a WHERE a.NAM='"+street+"' AND a.VID='"+prefix+"' ORDER BY a.DOM, a.KRP"), con);
                DataTable buildings = new DataTable();
                da.Fill(buildings);

                for (int j = 0; j < buildings.Rows.Count; j++)
                {



                    string number = buildings.Rows[j][0].ToString();
                    string housing = buildings.Rows[j][1].ToString();


                    XElement x_building = new XElement("building", new XAttribute("number",number), new XAttribute("housing",housing));
                    x_street.Add(x_building);

                    da = new OleDbDataAdapter(q("SELECT DISTINCT a.KVR, a.SKV, a.CK, s.JL FROM $addr as a LEFT JOIN $mt as s ON (s.CK = a.CK) WHERE a.NAM='" + street + "' AND a.VID='" + prefix + "' AND a.DOM=" + number + " AND a.KRP" + ((housing.Length>0)?"='"+housing+"'":" is null") + " ORDER BY a.KVR, A.SKV, a.CK"), con);
                    
                    DataTable apartments = new DataTable();
                    da.Fill(apartments);

                    for (int a = 0; a < apartments.Rows.Count; a++)
                    {
                        string kv = apartments.Rows[a][0].ToString();
                        string lit = apartments.Rows[a][1].ToString();
                        string ls = apartments.Rows[a][2].ToString();
                        string jl = apartments.Rows[a][3].ToString();



                        XElement x_apartment = new XElement("apartment", new XAttribute("number",kv), new XAttribute("lit",lit), new XAttribute("ls",ls), new XAttribute("people",jl));
                        x_building.Add(x_apartment);

                        da = new OleDbDataAdapter(q("SELECT s.ID, s.USL, s.STATUS, s.DTY, s.DTM, S.VAL FROM $mt as s WHERE s.CK="+ls), con);
                        DataTable meters = new DataTable();
                        da.Fill(meters);

                        for (int k = 0; k < meters.Rows.Count; k++)
                        {
                            string id = meters.Rows[k][0].ToString();
                            string usl = meters.Rows[k][1].ToString();
                            string stat = meters.Rows[k][2].ToString();
                            string dty = meters.Rows[k][3].ToString();
                            string dtm = meters.Rows[k][4].ToString();
                            string val = meters.Rows[k][5].ToString();

                            XElement x_meter = new XElement("meter");
                            x_meter.Add(new XAttribute("id",id));
                            x_meter.Add(new XAttribute("service_id", getService(usl)[0]));
                            x_meter.Add(new XAttribute("status_id", stat));
                            x_meter.Add(new XAttribute("last_date", new DateTime(int.Parse(dty),int.Parse(dtm),1).ToShortDateString()));
                            x_meter.Add(new XAttribute("last_val", val));
                            x_apartment.Add(x_meter);
                        }

                    }
                }
                
            }

            da = new OleDbDataAdapter(q("SELECT DISTINCT s.USL FROM $mt as s"), con);
            DataTable services = new DataTable();
            da.Fill(services);

            XElement srv = new XElement("services");
            for (int i = 0; i < services.Rows.Count; i++)
            {
                XElement service = new XElement("service", new XAttribute("id", getService(services.Rows[i][0].ToString())[0]), new XAttribute("name", services.Rows[i][0].ToString()));
                srv.Add(service);
            }

            root.Add(srv);

            doc.Save(DateTime.Now.ToShortDateString() + ".xml");


        }

        private void linkLabel2_LinkClicked(object sender, LinkLabelLinkClickedEventArgs e)
        {
            
        }
    }
}
