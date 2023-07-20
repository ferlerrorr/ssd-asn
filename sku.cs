using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Data.OleDb;

using MySql.Data.MySqlClient;
using System.Text.RegularExpressions;

namespace ConsoleApplication3
{
    class Program
    {
        static void Main(string[] args)
        {
            MySqlConnection connection = new MySqlConnection("server='localhost'; DATABASE=asn_db;username=root; password=");

           
            string AS400ConStr = "Provider=IBMDA400" +

                 
            ";Data Source=10.88.40.36" +
            ";User Id=IFSSPOS2" +
            ";Password= $$D1i2O16";
           
            Console.WriteLine("Trying to connect to AS400 DB2 using Client Access .dll");
            OleDbConnection conn = new OleDbConnection(AS400ConStr);


            try
            {
                conn.Open();
                if (conn != null)
                {
                    string varDate = DateTime.Now.AddDays(-60).ToString("yyMMdd");
                    Console.WriteLine("Successfully connected...");

        
                    
                    string qry = "SELECT POSTAT,PONOT1,POVNUM, PONUMB, POEDAT FROM MM770SSL.POMHDR " +
                                 "WHERE POEDAT >= " + varDate + " " +
                                 "ORDER BY PONUMB DESC";

                    



                    OleDbCommand comm = conn.CreateCommand();
                    comm.CommandText = qry;
           
                    OleDbDataReader reader = comm.ExecuteReader();

                    connection.Open();
                    MySqlCommand command = new MySqlCommand();

                    while (reader.Read())
                    {
                       
                            if (reader["PONUMB"] != null && reader["POVNUM"] != null && reader["PONUMB"] != "" && reader["POVNUM"] != "")
                        {
                            Console.WriteLine(reader["PONOT1"] + " : " + reader["PONUMB"] + " : " + reader["POVNUM"] + " : " + reader["POEDAT"] + " : " + reader["POSTAT"]);


                                 string insertQuery = "INSERT IGNORE INTO jda_pomhdr (jp_PONUMB,jp_POVNUM,jp_PONOT1,jp_POSTAT) VALUES('" + reader["PONUMB"] + "','" + reader["POVNUM"] + "','" + reader["PONOT1"] + "','" + reader["POSTAT"] + "')";

                                command = new MySqlCommand(insertQuery, connection);
                                command.ExecuteNonQuery();
                                command.Dispose();
                            //}
                        }
                    }

                    reader.Close();
                    comm.Dispose();


                }

            }
            catch (Exception ex)
            {
                Console.WriteLine("Error : " + ex);
                Console.WriteLine(ex.StackTrace);
            }
            finally
            {
                connection.Close();
                conn.Close();
            }





        }
    }
}
