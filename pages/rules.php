<?
  $phases = $engine->getPhases();

?><div id="headline"><h1>R�glement</h1></div>
	<div class="maincontent">
    <p>
      <span class="rule_title">I. R�alisation des votes</span>
      <br />
      Le pronostic d'un match peut �tre effectu� jusqu'� 15 minutes avant le d�but du match.<br />
      Pour �tre valide, le pronostic doit comporter le score marqu� de chaque �quipe.<br />
      <br />
      <br />
    </p>

		<p>
      <span class="rule_title">II. Attribution des points</span>
      <br />
      <p>
        <span class="rule_subtitle">A. Points "r�sultat"</span> :
        <ul class="rules">
          <li>Accord�s lorsque le pronostiqueur a trouv� le vainqueur du match ou le match nul le cas �ch�ant.</li>
          <li>Tableau des points "r�sultat" :
            <br /><br />
      		  <table class="rules">
      			  <caption>R�gles d'attribution des points par phase :</caption>
      			  <tr>
      				  <th>Phase</th>
      				  <th>Nb de matchs</th>
      				  <th>R�sultat juste</th>
      			  </tr>
<? foreach($phases as $phase) { ?>
      		 	  <tr>
      				  <td><? echo $phase['name']; ?></td>
      				  <td><? echo $phase['nb_matchs']; ?></td>
      				  <td><? echo $phase['nbPointsRes']; ?></td>
      			  </tr>
<? } ?>
      		  </table>
          </li>
        </ul>
        <br />
      </p>

      <p>
        <span class="rule_subtitle">B. Points "bonus"</span> :
        <ul class="rules">
          <li>Les points de bonus sont d�termin�s en calculant la diff�rence entre la valeur pronostiqu�e et la valeur r�elle :
            <ul>
              <li>pour le score de l'�quipe A,</li>
              <li>pour le score de l'�quipe B,</li>
              <li>ainsi que pour l'�cart du match (diff�rence entre les 2 scores).</li>
            </ul>
          </li>
          <li>Pr�cision importante : les points de bonus sont attribu�s si et seulement si le bon r�sultat a �t� pronostiqu�. Exemple : un pronostic de 15-10 pour un match se terminant par un score de 15-18 ne rapporte aucun point, m�me si le pronostiqueur avait pr�vu 15 points pour l'�quipe A.</li>
          <li>
            Il existe deux sortes de bonus : le bonus "juste" et le bonus "proche". Les points bonus "juste" sont accord�s lorsque la valeur pronostiqu�e et la valeur r�elle sont identiques ou quasi-identiques. Les points bonus "proche" sont accord�s lorsque la valeur pronostiqu�e et la valeur r�elle sont relativement proches. Le tableau suivant d�finit les zones "bonus juste" et "bonus proche".
            <table class="rules">
      			  <tr>
      				  <th>Si le score ou l'�cart r�el est compris entre :</th>
      				  <th>L'intervalle de tol�rance (IT) autour de la valeur r�elle (du score ou de l'�cart) permettant d'obtenir les points du "bonus juste" est de :</th>
      				  <th>L'intervalle de tol�rance (IT) autour de la valeur r�elle (du score ou de l'�cart) permettant d'obtenir les points du "bonus proche" est de :</th>
      			  </tr>
      			  <tr>
      			   <td>[ 00 ; 20 ]</td>
      			   <td>0</td>
      			   <td>[-4;-1] [+1;+4]</td>
      			  </tr>
      			  <tr>
      			   <td>[ 21 ; 40 ]</td>
      			   <td>[-2;+2]</td>
      			   <td>[-8;-3] [+3;+8]</td>
      			  </tr>
      			  <tr>
      			   <td>[ 41 ; 60 ]</td>
      			   <td>[-4;+4]</td>
      			   <td>[-12;-5] [+5;+12]</td>
      			  </tr>
      			  <tr>
      			   <td>[ 61 ; +oo ]</td>
      			   <td>[-6;+6]</td>
      			   <td>[-20;-7] [+7;+20]</td>
      			  </tr>
      			</table>
          </li>
          <li>Tableau des points bonus "juste" :
            <br /><br />
            <table class="rules">
      			  <tr>
      				  <th>Phase</th>
      				  <th>Nb de points</th>
      			  </tr>
<? foreach($phases as $phase) { ?>
      		 	  <tr>
      				  <td><? echo $phase['name']; ?></td>
      				  <td><? echo $phase['nbPointsScoreNiv1']; ?></td>
      			  </tr>
<? } ?>
      			</table>
          </li>
          <li>Tableau des points bonus "proche" :
            <br /><br />
            <table class="rules">
      			  <tr>
      				  <th>Phase</th>
      				  <th>Nb de points</th>
      			  </tr>
<? foreach($phases as $phase) { ?>
      		 	  <tr>
      				  <td><? echo $phase['name']; ?></td>
      				  <td><? echo $phase['nbPointsScoreNiv2']; ?></td>
      			  </tr>
<? } ?>
      			</table>
          </li>
        </ul>
        <br />
      </p>
      
      <p>
        <span class="rule_subtitle">C. Synth�se des points</span> :
        <table class="rules">
  			  <tr>
  				  <th>Phase</th>
  				  <th>Pts "r�sultat match"</th>
  				  <th>Pts "bonus" maxi / match</th>
  				  <th>Total pts / match</th>
  				  <th>Nb de matchs</th>
  				  <th>Total de pts</th>
  			  </tr>
<? foreach($phases as $phase) { ?>
  		 	  <tr>
  				  <td><? echo $phase['name']; ?></td>
  				  <td><? echo $phase['nbPointsRes']; ?></td>
  				  <td><? echo $phase['nbPointsScoreNiv1']; ?> x 3</td>
  				  <td><? echo ($phase['nbPointsRes'] + $phase['nbPointsScoreNiv1']*3); ?></td>
  				  <td><? echo $phase['nb_matchs']; ?></td>
  				  <td><? echo ($phase['nb_matchs'] * ($phase['nbPointsRes'] + $phase['nbPointsScoreNiv1']*3)); ?></td>
  			  </tr>
<? } ?>
      	</table>
        <br />
      </p>

      <p>
        <span class="rule_subtitle">D. Exemples (pour bien comprendre ...)</span> :
        <ul class="rules">
          <li>Exemple 1 : le r�sultat d'un match de poule est de 87-10.
            <ul class="rules">
              <li>Le joueur P1 a pronostiqu� 44-3. Il marque 10 points, soit :
                <ul>
                  <li>10 points pour le r�sultat,</li>
                  <li>0 point de bonus pour le score de l'�quipe A car 44-87 = -33 (-33 n'�tant pas dans les IT de bonus pour un score de 87 sup�rieur ou �gal � 61),</li>
                  <li>0 point de bonus pour le score de l'�quipe B car 3-10 = -7, (-7 n'�tant pas dans les IT de bonus pour un score de 3 compris entre 0 et 20),</li>
                  <li>0 point de bonus pour l'�cart car 41-77 = -36 (-36 n'�tant pas dans les IT de bonus pour un �cart de 77 sup�rieur ou �gal � 61).</li>
                </ul>
              </li>
              <li>Le joueur P2 a pronostiqu� 72-14. Il marque 13 points, soit :
                <ul>
                  <li>10 points pour le r�sultat,</li>
                  <li>1 point de bonus pour le score de l'�quipe A car 72-87 = -15 (-15 �tant dans l'IT du bonus proche pour un score de 87 sup�rieur ou �gal � 61),</li>
                  <li>1 point de bonus pour le score de l'�quipe B car 14-10 = 4, (4 �tant dans l'IT du bonus proche pour un score de 3 compris entre 0 et 20),</li>
                  <li>1 point de bonus pour l'�cart car 58-77 = -19 (-19 �tant dans l'IT du bonus proche pour un �cart de 77 sup�rieur ou �gal � 61).</li>
                </ul>
              </li>
              <li>Le joueur P3 a pronostiqu� 10-10 : bien qu'il ait trouv� le score de l'�quipe B, il ne marque aucun point car il n'a pas trouv� le r�sultat (il n'a pas d�sign� le bon vainqueur).</li>
            </ul>
          </li>
          <li>Exemple 2 : le r�sultat d'une demi-finale est de 25-12.
            <ul class="rules">
              <li>Le joueur P1 a pronostiqu� 12-16 : il ne marque aucun point car il n'a pas trouv� le r�sultat (il n'a pas d�sign� le bon vainqueur).</li>
              <li>Le joueur P2 a pronostiqu� 32-13. Il marque 20 points, soit :
                <ul>
                  <li>16 points pour le r�sultat,</li>
                  <li>2 points de bonus pour le score de l'�quipe A car 32-25 = 7 (7 �tant dans l'IT du bonus proche pour un score de 25 compris entre 20 et 40),</li>
                  <li>2 points de bonus pour le score de l'�quipe B car 13-12 = 1, (1 �tant dans l'IT du bonus proche pour un score de 12 compris entre 0 et 20),</li>
                  <li>0 point de bonus pour l'�cart car 19-13 = 6 (6 n'�tant pas dans les IT de bonus pour un �cart de 13 compris entre 0 et 20).</li>
                </ul>
              </li>
              <li>Le joueur P3 a pronostiqu� 27-12. Il marque 28 points, soit :
                <ul>
                  <li>16 points pour le r�sultat,</li>
                  <li>5 points de bonus pour le score de l'�quipe A car 27-25 = 2 (2 �tant dans l'IT du bonus juste pour un score de 25 compris entre 20 et 40),</li>
                  <li>5 points de bonus pour le score de l'�quipe B car 12-12 = 0, (0 �tant dans l'IT du bonus juste pour un score de 12 compris entre 0 et 20),</li>
                  <li>2 points de bonus pour l'�cart car 15-13 = 2 (2 �tant dans l'IT du bonus proche pour un �cart de 13 compris entre 0 et 20).</li>
                </ul>
              </li>
              <li>Le joueur P4 a pronostiqu� 38-25. Il marque 21 points, soit :
                <ul>
                  <li>16 points pour le r�sultat,</li>
                  <li>0 point de bonus pour le score de l'�quipe A car 38-25 = 13 (13 n'�tant pas dans les IT de bonus pour un score de 25 compris entre 20 et 40),</li>
                  <li>0 point de bonus pour le score de l'�quipe B car 25-12 = 13, (13 n'�tant pas dans les IT de bonus pour un score de 12 compris entre 0 et 20),</li>
                  <li>5 points de bonus pour l'�cart car 13-13 = 0 (0 �tant dans l'IT du bonus juste pour un �cart de 13 compris entre 0 et 20).</li>
                </ul>
              </li>
            </ul>
          </li>
        </ul>
      </p>
    </p>
	</div>
