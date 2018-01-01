<?php error_reporting(0) ?>
<?php require_once('lang.php'); ?>
<?php require_once('fonctions.php'); ?>
<?php require('fpdf/fpdf.php');

class PDF extends FPDF
{

	// Chargement des données
	function LoadData()
	{
		$data = getPaiementsData($_REQUEST['start'],$_REQUEST['end'],$_REQUEST['sql']);
		return $data;
	}
	
	function getHeaderData(){
		$dta=array();
		$dta[0][0]="Date Pointage entre : ".$_REQUEST['start']." et : ".$_REQUEST['end'];
		return $dta;
	}
// En-tête
	function Header()
	{
		
		$id_client=1;
		$facture_num=10;
		$date = date("d-m-Y");
		$heure = date("H:i");
		// Logo
		$this->Image('logo_header.png',10,6,30);
		// Police Arial gras 15
		$this->SetFont('Arial','B',13);
		// Décalage à droite
		$this->Cell(25);
		// Titre
		$title=utf8_decode('Somlako');
		$this->Cell(60,10,$title,0,0,'C');
		$this->Cell(48);
		// Date
		$this->Cell(48,10,$heure,0,0,'C');
		$this->Cell(1);
		// Titre
		$this->Cell(1,10,$date,0,0,'C');
		// Saut de ligne
		$this->Ln(5);
		$this->Cell(30);
		// Titre
		
		$this->SetFont('Arial','B',13);
		// Décalage à droite
		$this->Cell(5);
		// Titre
		$this->SetFont('Arial','B',20);
		$this->Cell(50);
		// Titre
		$fact=utf8_decode('Paiement N° :');
		$this->Cell(50,10,$fact,0,0,'C');
		$this->Cell(5);
		// Titre
		$this->Cell(5,10,$facture_num,0,0,'C');
		// Saut de ligne
		$this->Ln(20);
	}

	// Pied de page
	function Footer()
	{
		// Positionnement à 1,5 cm du bas
		$this->SetY(-19);
		// Police Arial italique 8
		$this->SetFont('Arial','I',10);
		// adresse de page
		$this->Cell(0,10,"______________________________________________________________________",0,0,'C');
		$this->Ln(5);
		$adresse=utf8_decode('Dépostaire  Marrakech  Adresse: Lot Massira 2 A N° 829');
		$adress_half=utf8_decode('Tél: +212 661 369 412 / +212 600 531 642');
		$this->Cell(0,10,$adresse,0,0,'C');
		$this->Ln(5);
		$this->Cell(0,10,$adress_half,0,0,'C');
		// Numéro de page
		$this->Cell(1,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
	// Tableau simple
	function BasicTable($header, $data)
	{
		// En-tête
		foreach($header as $col)
			$this->Cell(40,7,$col,1);
		$this->Ln();
		// Données
		foreach($data as $row)
		{
			foreach($row as $col)
				$this->Cell(40,6,$col,1);
			$this->Ln();
		}
	}

	// Tableau amélioré
	function ImprovedTable($header, $data)
	{
		//print_r($data);
		// Largeurs des colonnes
		$wi=28;
		$w = array(15,2*$wi,$wi,$wi,40,$wi,$wi,$wi,30);
		// En-tête
		for($i=0;$i<count($header);$i++)
			$this->Cell($w[$i],9,$header[$i],1,0,'C');
		$this->Ln();
		// Données
		foreach($data as $row)
		{
			$this->Cell($w[0],6,$row[0],'LR');
			$this->Cell($w[1],6,$row[1],'LR',0,'R');
			$this->Cell($w[2],6,$row[2],'LR',0,'R');
			$this->Cell($w[3],6,$row[3],'LR',0,'R');
			$this->Cell($w[4],6,$row[4],'LR',0,'R');
			$this->Cell($w[5],6,$row[5],'LR',0,'R');
			$this->Cell($w[6],6,$row[6],'LR',0,'R');
			$this->Cell($w[7],6,$row[7],'LR',0,'R');
			$this->Cell($w[8],6,$row[8],'LR',0,'R');
			$this->Ln();
		}
		// Trait de terminaison
		$this->Cell(array_sum($w),0,'','T');
	}

	// Tableau coloré
	function FancyTable($header, $data)
	{
		// Couleurs, épaisseur du trait et police grasse;
		$this->SetTextColor(0);
		$this->SetDrawColor(0,0,0);
		$this->SetLineWidth(.2);
		$this->SetFont('','B');
		// En-tête
		$wi=28;
		$w = array(130,95);
		/*for($i=0;$i<count($header);$i++)
			$this->Cell($w[$i],7,$header[$i],0,0,'L');
		$this->Ln();*/
		// Restauration des couleurs et de la police
		$this->SetFillColor(224,235,255);
		$this->SetTextColor(0);
		$this->SetFont('');
		// Données
		$fill = false;
		foreach($data as $row)
		{
			$this->Cell($w[0],6,$row[0],0,0,'L');
			//$this->Cell($w[1],6,$row[1],0,0,'L');
			$this->Ln();
			$fill = !$fill;
		}
		// Trait de terminaison
		//$this->Cell(array_sum($w),0,'','T');
	 }
	}

	$pdf = new PDF();
	$pdf->AliasNbPages();
	// Titres des colonnes
	$designation=utf8_decode('Désignation');
	$header = array('Code', 'Nom', 'Heurs N','Heurs S', 'Tarif', 'Montant', 'Avance', 'Credit', 'Net à payer');
	$header2 = array('','');
	// Chargement des données
	$pdf->Ln(30);
	$dta = $pdf->getHeaderData();
	$pdf->SetFont('Arial','',14);
	$pdf->AddPage("L");
	$pdf->FancyTable($header,$dta);
	$pdf->Ln(10);
	$data = $pdf->LoadData();
	$pdf->SetFont('Arial','',14);
	
	$pdf->ImprovedTable($header,$data);
	$pdf->Output('paiement.pdf','D');
	
?>