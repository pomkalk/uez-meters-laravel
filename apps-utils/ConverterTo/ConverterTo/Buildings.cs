using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Xml.Linq;

namespace ConverterTo
{
    class Building
    {
        int id;
        public string number;
        public string housing;
        public XElement xml;
        public Dictionary<string, Apartment> apartments = new Dictionary<string, Apartment>();

        public Building(int id,string number, string housing)
        {
            this.id = id;
            this.number = number;
            this.housing = housing;

            this.xml = new XElement("building", new XAttribute("id", id), new XAttribute("number", number), new XAttribute("housing", housing));
        }
    }
}
