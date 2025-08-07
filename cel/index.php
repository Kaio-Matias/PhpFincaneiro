<?
include("aplicacoes.php");
if(!isset($t)) { $t = 'info';}		
?>
<TABLE class=border cellSpacing=0 cellPadding=0 width="630" align=center border=0>
  <TBODY> 
  <TR> 
    <TD>
      <TABLE cellSpacing=1 cellPadding=5 width="100%" border=0>
        <TBODY> 
        <TR> 
          <TD class=hf align=right>&nbsp;</TD>
        </TR>
        <TR> 
          <TD class=back> 
            <TABLE width="100%" align=center border=0 cellSpacing=0 cellPadding=0>
  			 <TBODY> 
					<tr><td colspan=2 align=center><IMG SRC="images/coletadeleite.gif"></td></tr>
			  <TR> 
				<TD vAlign=top width="25%"> 
				  <TABLE class=border cellSpacing=0 cellPadding=0 width="97%" align=left border=0>
					<TBODY> 
					<TR> 
                      <TD> 
                        <TABLE cellSpacing=1 cellPadding=5 width="100%" border=0>
                          <TBODY> 
                          <TR> 
                            <TD class=info align=middle><B>Opções do Posto</B></TD>
                          </TR>
                          <TR> 
                            <TD class=cat><B>Opções de Coleta</B></TD>
                          </TR>
                          <TR> 
                            <TD class=subcat> 
							  <LI><A href="index.php?t=entra">Entrada de Leite</A></LI>
                              <LI><A href="index.php?t=sai">Saída de Leite</A></LI>
                              <LI><A href="index.php?t=info">Saldo de Leite</A></LI>
                              <LI><A href="index.php?t=conf">Conferir Leite</A></LI>
                            </TD>
                          </TR>   
                          <TR> 
                            <TD class=cat><B>Opções de Dados</B></TD>
                          </TR>
                          <TR> 
                            <TD class=subcat> 
							  <LI><A href="index.php?t=car">Receber Dados SCL</A></LI>
                              <LI><A href="index.php?t=des">Enviar Movimentação</A></LI>
                            </TD>
                          </TR> 
                          </TBODY> 
                        </TABLE>
                      </TD>
                    </TR>
                    </TBODY> 
                  </TABLE>
                </TD>
                <TD vAlign=top>

					<?php
					switch($t){
						case ("entra"):
							require "entrada.php";
							break;
						case ("Entrada"):
							require "entrada.php";
							break;
						case ("sai"):
							require "saida.php";
							break;
						case ("info"):
							require "info.php";
							break;
						case ("car"):
							require "carga.php";
							break;
						case ("des"):
							require "descarga.php";
							break;
						case ("conf"):
							require "conferencia.php";
							break;
					}
		  
					?>


              </TR>
              </TBODY> 
            </TABLE>
            <BR>
	          </TD>	  
		</TR>
        </TBODY> 
      </TABLE>
  </TR>
  </TBODY> 
</TABLE>

<br><center><font size=1>Valedourado<br>Gerência de Tecnologia da Infomação - </b> v1.0<br></font> </center>
</BODY>

</HTML>