<?php

namespace App\Http\Controllers;
use App\solicitante;
use App\beneficiario;
use App\peticion;
use App\periodo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

use App\Http\Requests\createSolicitantesRequest;
use App\Http\Requests\createPeticionRequest;

class solicitantesController extends Controller
{
    //
     public function show(){   
    	$solicitantes=solicitante::latest()->paginate(8);  
    	   return view('solicitantes.showSoli',[
    		'solicitantes'=> $solicitantes,
    		]);
    }

    public function buscar($id){
    $solicitante = solicitante::find($id);
    return Response::json($solicitante);

    } 
    public function create(createSolicitantesRequest $request){
    	//dd($request->all());
    	$message= solicitante::create($request->all());
		//$response = socio::create($request->all());	
   		 
    	return Response::json($message);
    	
    	
    	return redirect('/socios')->with('mensaje','Registro Guardado');

    }
    public function createPeticion(createPeticionRequest $request){
        //dd($request->all());
        $periodo=periodo::where('estado','Iniciado')->get()->first();
       $message= peticion::create([
            'titulo'=> $request->input('titulo'),
            'descripcion'=> $request->input('descripcion'),
            'idbeneficiarios'=> $request->input('bene_id'),
            'idsolicitantes'=> $request->input('soli_id'),
            'estado'=>'Disponible',
            'idperiodos'=>$periodo->id,
            ]);
        return Response::json($message);
        
        
        return redirect('/socios')->with('mensaje','Registro Guardado');

    }
    public function update(createSolicitantesRequest $request,$id){
    	//dd($request->all());
    	$message = solicitante::find($id);
    	 $message->fill($request->all());
    	$message->save();
    	return Response::json($message);
    	//return response(json($array));
    }

    public function busqueda($texto){
    	$output="";
    	$messages=solicitante::where('nombre','like','%'.$texto.'%')
    	->orWhere('apellido','like','%'.$texto.'%')
    	->orWhere('dui','like','%'.$texto.'%')
    	->get();
    	if($messages){
    		foreach ($messages as $key => $message) {
    			$output.='<tr>'.
    					'<td>'.$message->nombre.'</td>'.
    					'<td>'.$message->apellido.'</td>'.
    					'<td>'.$message->dui.'</td>'.
    					'<td>'.$message->telefono.'</td>'.
    '<td class="text-center">'.
    '<button type="button" class="btn btn-outline-primary btn-sm peticionModal" value="'.$message->id.'">'.
    'Crear Peticion</button> '.
    '<button type="button" class="btn btn-outline-info btn-sm infomodal" value="'.$message->id.'">'.
    'Info</button> '.
    '<button type="button" class="btn btn-outline-success btn-sm editModal" value="'.$message->id.'">Editar</button> </td>'.
    '</tr>';
    		}
    		return Response::json($output);
    	}
    		return Response::json($messages);
    	
    }
    public function busquedaSelect(){
        $beneficiario=beneficiario::get(); 
    $array=array();
    $con=0;
    foreach($beneficiario as $beneficiarios)
    {
        $array[$con]=([
        'ide'=>$beneficiarios->id,
        'nombre'=>$beneficiarios->nombre,
        'apellido'=>$beneficiarios->apellido,
        ]);  
        $con++;
    }
        return Response::json($beneficiario);  
    }


}
