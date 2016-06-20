using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Xml.Linq;


namespace ConverterTo
{
    class Meter
    {
        int id;
        public string mid;
        public string usl;
        public string status;
        public string dt;
        public string val;
        public XElement xml;


        List<string> getService(string name)
        {
            List<string> res = new List<string>();
            switch (name)
            {
                case "Холодная вода":
                    res.Add("1");
                    res.Add("Холодная вода");
                    break;
                case "Горячая вода":
                    res.Add("2");
                    res.Add("Горячая вода");
                    break;
                case "Электроэнергия":
                    res.Add("4");
                    res.Add("Электроэнергия");
                    break;
                case "Отопление":
                    res.Add("3");
                    res.Add("Отопление");
                    break;
            }

            return res;
        }

        public Meter(int id, string mid, string usl, string status, string dty, string dtm, string val)
        {
            this.id = id;
            this.mid = mid;
            this.usl = usl;
            this.status = status;
            this.dt = new DateTime(int.Parse(dty), int.Parse(dtm), 1).ToShortDateString();
            this.val = val;

            this.xml = new XElement("meter");
            this.xml.Add(new XAttribute("id", id));
            this.xml.Add(new XAttribute("mid", mid));
            this.xml.Add(new XAttribute("service", getService(usl)[0]));
            this.xml.Add(new XAttribute("status", status));
            this.xml.Add(new XAttribute("last_date", dt));
            this.xml.Add(new XAttribute("last_value", val));

        }
    }
}
