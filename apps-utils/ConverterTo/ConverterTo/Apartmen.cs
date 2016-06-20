using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Xml.Linq;

namespace ConverterTo
{
    class Apartment
    {
        int id;
        public string number;
        public string lit;
        public string people;
        public string ls;
        public string space;
        public XElement xml;
        public Dictionary<string, Meter> meters = new Dictionary<string, Meter>();


        public Apartment(int id,string number, string lit, string people, string ls, string space)
        {
            this.id = id;
            this.number = number;
            this.lit = lit;
            this.people = people;
            this.ls = ls;
            this.space = space;

            this.xml = new XElement("apartment", new XAttribute("id", id), new XAttribute("number", number), new XAttribute("lit", lit), new XAttribute("people", (people == "") ? "0" : people), new XAttribute("ls", ls), new XAttribute("space", space));
        }

    }
}
