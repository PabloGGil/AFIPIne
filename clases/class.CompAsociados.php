<?php
/*
<ar:CbtesAsoc>
<ar:CbteAsoc>
<ar:Tipo>short</ar:Tipo>
<ar:PtoVta>int</ar:PtoVta>
<ar:Nro>Long</ar:Nro>
<ar:Cuit>String</ar:Cuit>
<ar:CbteFch>String</ar:CbteFch>
</ar:CbteAsoc>
</ar:CbtesAsoc>
*/
class CompAsociados{

    private $comprobantes=array();
    // private $Tipo;
    // private $PtoVta;
    // private $Nro;
    // private $Cuit;
    // private $CbteFch;
    // private $comprobante;

    public function agregar($comp){
        $comprobante=['CbteAsoc'=>[
        // $comprobante=[
            'Tipo'=>$comp['Tipo'],
            'PtoVta'=>$comp['PtoVta'],
            'Nro'=> $comp['Nro'],
            'Cuit'=> $comp['Cuit'],
            'CbteFch'=>$comp['CbteFch']
        ],
        ];
        array_push($this->comprobantes, $comprobante);
    }

    public function getComprobantes(){
        return $this->comprobantes;
    }

    

}

// -------------- prueba de la clase -------------------
// split