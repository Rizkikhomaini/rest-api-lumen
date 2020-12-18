<?php



namespace App\Http\Controllers;



use App\News;

use App\Helper;

use Illuminate\Http\Request;



class NewsController extends Controller

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



	var $response_key = "news";



	public function all()
	{

		$data = News::all();

		// ln -s sourceforwhich symnlinkfolderpath e.g.:

		// ln -s /data/html/projectfolder/storage/app/public /data/html/projectfolder/public/storage 

		foreach ($data as $d) {
			$d->image = 'your hosting here' . $d->image;
		}


		return Helper::response(array($this->response_key => $data));
	}



	public function byId($id)
	{

		$data = News::where('id', $id)->first();
		if ($data) {
			$data->image = 'your hosting here' . $data->image;
			return Helper::response(array("news_detail" => $data));
		} else {
			return Helper::response(array("news_detail" => array()), 400, "Tidak ada data.");
		}
	}


	public function store(Request $request)
	{
		if ($request->hasFile('image')) {
			$file = $request->file('image');
			$extension = $file->getClientOriginalExtension();
			$milliseconds = round(microtime(true) * 1000);
			$id_admin = $request->input('admin_id');
			$fileName = $id_admin . '_' . $milliseconds . '.' . $extension;
			$file->move(storage_path("/public/images/"), $fileName);

			$data = new News();

			$data->title = $request->input('title');
			$data->content = $request->input('content');
			$data->writer = $request->input('writer');
			$data->post_date = $request->input('post_date');
			$data->image = $fileName;
			$data->admin_id = $id_admin;

			$data->save();
			$data = News::where('id', $data->id)->first();
			return Helper::response(array("insert" => $data));
		}
	}



	public function update(Request $request, $id)
	{
		$data = News::find($id);
		if ($data) {
			$id_admin = $request->input('admin_id');

			if ($request->hasFile('image')) {
				$file = $request->file('image');
				$extension = $file->getClientOriginalExtension();
				$milliseconds = round(microtime(true) * 1000);
				$fileName = $id_admin . '_' . $milliseconds . '.' . $extension;
				$file->move(storage_path("/public/images/"), $fileName);

				$data->image = $fileName;
			}

			$data->title = $request->input('title');
			$data->content = $request->input('content');
			$data->writer = $request->input('writer');
			$data->post_date = $request->input('post_date');
			$data->admin_id = $id_admin;

			$data->save();

			$data = News::find($data->id);
			return Helper::response(array("update" => $data));
		} else {
			return Helper::response(array("update" => array("error" => "not found")), 400, "Data tidak ada.");
		}
	}



	public function destroy($id)
	{

		$data = News::find($id);

		$data->delete();



		return Helper::response(array($this->response_key => array()));
	}
}
