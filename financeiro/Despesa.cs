
using System;

namespace Financeiro
{
    public class Despesa
    {
        public int Codigo { get; set; }
        public DateTime Data { get; set; }
        public int Grupo { get; set; }
        public string Descricao { get; set; }
        public decimal ValDespesa { get; set; }
        public decimal ValAutoriz { get; set; }
        public decimal ValPago { get; set; }
        public int Autoriza { get; set; }
        public int Pagador { get; set; }
        public string ObsDir { get; set; }
        public DateTime Vencimento { get; set; }
        public int Centro { get; set; }
        public DateTime VencOrig { get; set; }
        public int CodOrig { get; set; }
    }
}
