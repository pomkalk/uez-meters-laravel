using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Xml.Linq;


namespace ConverterTo
{
    class Street
    {
        public int id;
        public string name;
        public string prefix;
        public XElement xml;
        public Dictionary<string, Building> buildings = new Dictionary<string, Building>();

        public Street(int id, string name, string prefix)
        {
            this.id = id;
            this.name = name;
            this.prefix = prefix;

            this.xml = new XElement("street", new XAttribute("id", id), new XAttribute("name", name), new XAttribute("prefix", prefix));
        }
    }
}
