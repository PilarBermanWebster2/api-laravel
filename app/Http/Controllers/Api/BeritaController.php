<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Storage;

class BeritaController extends Controller
{
    public function index()
    {
        $berita = Berita::with("kategori", "tag", "user")->get();
        $res = [
            "success" => true,
            "message" => "Data berita",
            "data" => $berita,
        ];
        return response()->json($res, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "nama_berita" => "required|unique:beritas",
            "deskripsi" => "required",
            "foto" => "required|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
            "id_user" => "required",
            "id_kategori" => "required",
            "tag" => "required|array",
        ]);

        if ($validator->fails()) {
            $res = [
                "success" => false,
                "message" => "Validasi Gagal",
                "errors" => $validator->errors(),
            ];
            return response()->json($res, 422);
        }

        try {
            $berita = new Berita();
            $berita->nama_berita = $request->nama_berita;
            $berita->deskripsi = $request->deskripsi;
            $berita->slug = Str::slug($request->nama_berita);
            if ($request->hasFile("foto")) {
                $image = $request->file("foto");
                $filename =
                    random_int(100000, 999999) .
                    "." .
                    $image->getClientOriginalExtension();
                $location = public_path("images/berita/" . $filename);
                $image->move($location, $filename);
                $berita->foto = $filename;
            }
            $berita->id_user = $request->id_user;
            $berita->id_kategori = $request->id_kategori;
            $berita->save();
            // melampirkan banyak tag
            $berita->tag()->attach($request->tag);
            // mengembalikan data
            $res = [
                "success" => true,
                "message" => "Data berita Tersimpan",
                "data" => $berita,
            ];
            return response()->json($res, 201);
        } catch (\Exception $e) {
            $res = [
                "success" => false,
                "message" => "Terjadi kesalahan",
                "errors" => $e->getMessage(),
            ];
            return response()->json($res, 500);
        }
    }

    public function show(string $id)
    {
        try {
            $berita = Berita::findOrFail($id);
            return response()->json(
                [
                    "success" => true,
                    "message" => "detail berita",
                    "data" => $berita,
                ],
                200
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "data not found",
                    "errors" => $e->getMessage(),
                ],
                404
            );
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            "nama_berita" => "required",
            "deskripsi" => "required",
            "foto" => "nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
            "id_user" => "required",
            "id_kategori" => "required",
            "tag" => "required|array",
        ]);

        if ($validator->fails()) {
            $res = [
                "success" => false,
                "message" => "Validasi Gagal",
                "errors" => $validator->errors(),
            ];
            return response()->json($res, 422);
        }

        try {
            $berita = Berita::findOrFail($id);
            $berita->nama_berita = $request->nama_berita;
            $berita->deskripsi = $request->deskripsi;
            $berita->slug = Str::slug($request->nama_berita);
            if ($request->hasFile("foto")) {
                $image = $request->file("foto");
                $filename =
                    random_int(100000, 999999) .
                    "." .
                    $image->getClientOriginalExtension();
                $location = public_path("images/berita/" . $filename);
                $image->move($location, $filename);
                $berita->foto = $filename;
            }
            $berita->id_user = $request->id_user;
            $berita->id_kategori = $request->id_kategori;
            $berita->save();
            // Update tags
            $berita->tag()->sync($request->tag);
            // mengembalikan data
            $res = [
                "success" => true,
                "message" => "Data berita Tersimpan",
                "data" => $berita,
            ];
            return response()->json($res, 200);
        } catch (\Exception $e) {
            $res = [
                "success" => false,
                "message" => "Terjadi kesalahan",
                "errors" => $e->getMessage(),
            ];
            return response()->json($res, 500);
        }
    }

    public function destroy($id)
    {
        try {
            $berita = Berita::findOrFail($id);

            // Delete the image file if it exists
            if (
                $berita->foto &&
                Storage::exists("public/images/berita/" . $berita->foto)
            ) {
                Storage::delete("public/images/berita/" . $berita->foto);
            }

            // Detach tags associated with this Berita
            $berita->tag()->detach();

            // Delete the Berita record
            $berita->delete();

            $res = [
                "success" => true,
                "message" => "Data Berita Deleted",
            ];
            return response()->json($res, 200);
        } catch (\Exception $e) {
            $res = [
                "success" => false,
                "message" => "Data Not Found or Failed to Delete",
                "errors" => $e->getMessage(),
            ];
            return response()->json($res, 500);
        }
    }
}
