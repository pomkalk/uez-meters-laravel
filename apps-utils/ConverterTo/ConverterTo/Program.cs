using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using System.Windows.Forms;
using System.IO;

namespace ConverterTo
{
    static class Program
    {
        /// <summary>
        /// Главная точка входа для приложения.
        /// </summary>
        [STAThread]
        static void Main()
        {
            if (!File.Exists("S_ADDR.DBF"))
            {
                MessageBox.Show("Файл S_ADDR.DBF не найден.", "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);

            }
            else
            {
                if (!File.Exists("S_SC.DBF"))
                {
                    MessageBox.Show("Файл S_SC.DBF не найден.", "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);

                }
                else
                {
                    Application.EnableVisualStyles();
                    Application.SetCompatibleTextRenderingDefault(false);
                    Application.Run(new Form1());
                }
            }


            
        }
    }
}
