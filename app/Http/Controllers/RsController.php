<?php



namespace App\Http\Controllers;



use App\RS;

use App\Helper;

use Illuminate\Http\Request;



class RsController extends Controller

{

	/**

	 * Create a new controller instance.

	 *

	 * @return void

	 */

	public function __construct()

	{

		//

	}



	var $response_key = "rs";



	public function all()
	{

		$data = RS::all();

		// ln -s sourceforwhich symnlinkfolderpath e.g.:

		// ln -s /data/html/projectfolder/storage/app/public /data/html/projectfolder/public/storage 

		foreach ($data as $d) {
			$d->gambar = 'your hosting here' . $d->gambar;
		}


		return Helper::response(array($this->response_key => $data));
	}



	public function byId($id)
	{

		$data = RS::where('id', $id)->first();
		if ($data) {
			$data->gambar = 'your hosting here' . $data->gambar;
			return Helper::response(array("rs_detail" => $data));
		} else {
			return Helper::response(array("rs_detail" => array()), 400, "Tidak ada data.");
		}
	}

	public function getNearbyRs(Request $request)
	{

		$latitude = $request->get('latitude');
		$longitude = $request->get('longitude');

		if ($latitude == "" || $longitude == "") {
			return Helper::response(array($this->response_key => array()), 400, "Param kurang");
		}

		$radius = $request->get('radius');

		if ($radius == "") {
			$radius = 5;
		}

		// https://stackoverflow.com/questions/574691/mysql-great-circle-distance-haversine-formula

		$rs = RS::select('tbl_rumahsakit.*')
			->selectRaw('( 6371 * acos( cos( radians(?) ) *
							   cos( radians( latitude ) )
							   * cos( radians( longitude ) - radians(?)
							   ) + sin( radians(?) ) *
							   sin( radians( latitude ) ) )
							 ) AS distance', [$latitude, $longitude, $latitude])
			->havingRaw("distance < ?", [$radius])
			->orderByRaw("distance asc")
			->get();

		foreach ($rs as $d) {
			$d->gambar = 'your hosting here' . $d->gambar;
		}

		return Helper::response(array($this->response_key => $rs));
	}


	public function store(Request $request)
	{
		if ($request->hasFile('gambar')) {
			$file = $request->file('gambar');
			$extension = $file->getClientOriginalExtension();
			$milliseconds = round(microtime(true) * 1000);
			$id_admin = $request->input('id_admin');
			$fileName = $id_admin . '_' . $milliseconds . '.' . $extension;
			$file->move(storage_path("/public/images/"), $fileName);

			$data = new RS();

			$data->nama_rs = $request->input('nama_rs');
			$data->deskripsi = $request->input('deskripsi');
			$data->alamat = $request->input('alamat');
			$data->telepon = $request->input('telepon');
			$data->website = $request->input('website');
			$data->gambar = $fileName;
			$data->jml_kamar = $request->input('jml_kamar');
			$data->latitude = $request->input('latitude');
			$data->longitude = $request->input('longitude');
			$data->id_admin = $id_admin;

			$data->save();
			$data = RS::where('id', $data->id)->first();
			return Helper::response(array("insert" => $data));
		}
	}



	public function update(Request $request, $id)
	{
		$data = RS::find($id);
		if ($data) {
			$id_admin = $request->input('id_admin');

			if ($request->hasFile('gambar')) {
				$file = $request->file('gambar');
				$extension = $file->getClientOriginalExtension();
				$milliseconds = round(microtime(true) * 1000);
				$fileName = $id_admin . '_' . $milliseconds . '.' . $extension;
				$file->move(storage_path("/public/images/"), $fileName);

				$data->gambar = $fileName;
			}

			$data->nama_rs = $request->input('nama_rs');
			$data->deskripsi = $request->input('deskripsi');
			$data->alamat = $request->input('alamat');
			$data->telepon = $request->input('telepon');
			$data->website = $request->input('website');
			$data->jml_kamar = $request->input('jml_kamar');
			$data->latitude = $request->input('latitude');
			$data->longitude = $request->input('longitude');
			$data->id_admin = $id_admin;

			$data->save();

			$data = RS::find($data->id);
			return Helper::response(array("update" => $data));
		} else {
			return Helper::response(array("update" => array("error" => "not found")), 400, "Data tidak ada.");
		}
	}



	public function destroy($id)
	{

		$data = RS::find($id);

		$data->delete();



		return Helper::response(array($this->response_key => array()));
	}
}
