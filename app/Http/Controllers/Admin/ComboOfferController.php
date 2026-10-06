<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\ComboOffer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ComboOfferController extends Controller
{
    public function index(Request $request)
    {
        $query = ComboOffer::with('product');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where('title', 'like', "%{$q}%")
                  ->orWhere('badge_text', 'like', "%{$q}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $offers = $query->orderBy('sort_order', 'asc')->orderBy('id', 'desc')->paginate(15)->withQueryString();

        return view('admin.combo_offers.index', compact('offers'));
    }

    public function create()
    {
        $products = Product::where('status', 'active')->get();
        return view('admin.combo_offers.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'badge_text' => 'nullable|string|max:100',
            'offer_badge_text' => 'nullable|string|max:100',
            'offer_text' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'link' => 'nullable|string|max:500',
            'product_id' => 'nullable|exists:products,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'image_url' => 'nullable|url|max:500',
            'status' => 'required|in:active,inactive',
            'sort_order' => 'nullable|integer',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('combo_offers', 'public');
        } elseif ($request->filled('image_url')) {
            $imagePath = $request->image_url;
        } elseif ($request->filled('product_id')) {
            $product = Product::find($request->product_id);
            if ($product) {
                $imagePath = $product->image;
            }
        }

        $offer = ComboOffer::create([
            'title' => $request->title,
            'badge_text' => $request->badge_text ?? 'BIG COMBO OFFER',
            'offer_badge_text' => $request->offer_badge_text ?? 'BIG OFFER',
            'offer_text' => $request->offer_text ?? ('৳ ' . number_format($request->price)),
            'price' => $request->price,
            'link' => $request->link,
            'product_id' => $request->product_id,
            'image' => $imagePath,
            'status' => $request->status,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        AuditLog::log('Combo Offer Card Created', "নতুন কম্বো অফার কার্ড '{$offer->title}' তৈরি করা হয়েছে।");

        return redirect()->route('admin.combo-offers.index')
            ->with('success', "কম্বো অফার কার্ড '{$offer->title}' সফলভাবে যোগ করা হয়েছে!");
    }

    public function edit($id)
    {
        $offer = ComboOffer::findOrFail($id);
        $products = Product::where('status', 'active')->get();
        return view('admin.combo_offers.edit', compact('offer', 'products'));
    }

    public function update(Request $request, $id)
    {
        $offer = ComboOffer::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'badge_text' => 'nullable|string|max:100',
            'offer_badge_text' => 'nullable|string|max:100',
            'offer_text' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'link' => 'nullable|string|max:500',
            'product_id' => 'nullable|exists:products,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'image_url' => 'nullable|url|max:500',
            'status' => 'required|in:active,inactive',
            'sort_order' => 'nullable|integer',
        ]);

        $imagePath = $offer->image;
        if ($request->hasFile('image')) {
            if ($offer->image && !filter_var($offer->image, FILTER_VALIDATE_URL) && Storage::disk('public')->exists($offer->image)) {
                Storage::disk('public')->delete($offer->image);
            }
            $imagePath = $request->file('image')->store('combo_offers', 'public');
        } elseif ($request->filled('image_url')) {
            $imagePath = $request->image_url;
        }

        $offer->update([
            'title' => $request->title,
            'badge_text' => $request->badge_text ?? 'BIG COMBO OFFER',
            'offer_badge_text' => $request->offer_badge_text ?? 'BIG OFFER',
            'offer_text' => $request->offer_text ?? ('৳ ' . number_format($request->price)),
            'price' => $request->price,
            'link' => $request->link,
            'product_id' => $request->product_id,
            'image' => $imagePath,
            'status' => $request->status,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        AuditLog::log('Combo Offer Card Updated', "কম্বো অফার কার্ড '{$offer->title}' আপডেট করা হয়েছে।");

        return redirect()->route('admin.combo-offers.index')
            ->with('success', "কম্বো অফার কার্ড '{$offer->title}' সফলভাবে আপডেট করা হয়েছে!");
    }

    public function destroy($id)
    {
        $offer = ComboOffer::findOrFail($id);
        $title = $offer->title;

        if ($offer->image && !filter_var($offer->image, FILTER_VALIDATE_URL) && Storage::disk('public')->exists($offer->image)) {
            Storage::disk('public')->delete($offer->image);
        }

        $offer->delete();

        AuditLog::log('Combo Offer Card Deleted', "কম্বো অফার কার্ড '{$title}' মুছে ফেলা হয়েছে।");

        return redirect()->route('admin.combo-offers.index')
            ->with('success', "কম্বo অফার কার্ড '{$title}' সফলভাবে মুছে ফেলা হয়েছে!");
    }

    public function toggleStatus($id)
    {
        $offer = ComboOffer::findOrFail($id);
        $offer->status = $offer->status === 'active' ? 'inactive' : 'active';
        $offer->save();

        AuditLog::log('Combo Offer Status Toggled', "কম্বো অফার কার্ড '{$offer->title}' স্ট্যাটাস পরিবর্তন করে {$offer->status} করা হয়েছে।");

        return redirect()->back()->with('success', "কম্বো অফার কার্ড '{$offer->title}' স্ট্যাটাস সফলভাবে পরিবর্তন করা হয়েছে!");
    }
}
