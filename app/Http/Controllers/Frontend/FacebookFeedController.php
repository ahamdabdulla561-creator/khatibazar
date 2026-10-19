<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Response;

class FacebookFeedController extends Controller
{
    public function index(): Response
    {
        $products = Product::with(['category', 'brand', 'variants'])
            ->where('status', 'active')
            ->orderBy('id', 'desc')
            ->get();

        $siteName = site_setting('site_name', 'খাঁটি বাজার (Khati Bazar)');
        $siteUrl = url('/');

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">' . "\n";
        $xml .= '  <channel>' . "\n";
        $xml .= '    <title>' . htmlspecialchars($siteName, ENT_XML1, 'UTF-8') . ' - Facebook Product Catalog</title>' . "\n";
        $xml .= '    <link>' . htmlspecialchars($siteUrl, ENT_XML1, 'UTF-8') . '</link>' . "\n";
        $xml .= '    <description>' . htmlspecialchars('অর্গানিক বাদাম ও বীজ, খাঁটি মসলা এবং ফার্ম পণ্যের ফেসবুক ক্যাটালগ ফিড', ENT_XML1, 'UTF-8') . '</description>' . "\n";

        foreach ($products as $product) {
            $prodUrl = route('products.show', $product->slug);
            $imgUrl = $product->image_url;
            $brandName = $product->brand ? $product->brand->name : $siteName;
            $catName = $product->category ? $product->category->name : 'General';
            $price = number_format((float)$product->effective_price, 2, '.', '') . ' BDT';
            $regularPrice = number_format((float)$product->regular_price, 2, '.', '') . ' BDT';
            $availability = $product->inStock() ? 'in stock' : 'out of stock';
            $desc = strip_tags($product->short_description ?: ($product->description ?: $product->name));
            $desc = mb_substr(trim(preg_replace('/\s+/', ' ', $desc)), 0, 5000);

            $xml .= '    <item>' . "\n";
            $xml .= '      <g:id>' . htmlspecialchars($product->sku ?: ('KB-' . $product->id), ENT_XML1, 'UTF-8') . '</g:id>' . "\n";
            $xml .= '      <g:title><![CDATA[' . $product->name . ']]></g:title>' . "\n";
            $xml .= '      <g:description><![CDATA[' . $desc . ']]></g:description>' . "\n";
            $xml .= '      <g:link>' . htmlspecialchars($prodUrl, ENT_XML1, 'UTF-8') . '</g:link>' . "\n";
            $xml .= '      <g:image_link>' . htmlspecialchars($imgUrl, ENT_XML1, 'UTF-8') . '</g:image_link>' . "\n";
            $xml .= '      <g:brand><![CDATA[' . $brandName . ']]></g:brand>' . "\n";
            $xml .= '      <g:product_type><![CDATA[' . $catName . ']]></g:product_type>' . "\n";
            $xml .= '      <g:condition>new</g:condition>' . "\n";
            $xml .= '      <g:availability>' . $availability . '</g:availability>' . "\n";
            $xml .= '      <g:price>' . $price . '</g:price>' . "\n";
            if ($product->sale_price && $product->sale_price < $product->regular_price) {
                $xml .= '      <g:sale_price>' . $price . '</g:sale_price>' . "\n";
            }
            $xml .= '    </item>' . "\n";
        }

        $xml .= '  </channel>' . "\n";
        $xml .= '</rss>';

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);
    }
}
