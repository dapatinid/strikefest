<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tiket Strikefest 2025</title>

<style>
 
</style>

</head>
<body style="font-family: Arial, Helvetica, sans-serif; font-size: 18px; margin-bottom: 2em; padding-top: 2rem; padding-left: 1rem; padding-right: 1rem">
    <img src="{{ asset('/assets/images/LogoStrikefest2025.png') }}" alt="" style="display: block; margin: 0 auto; margin-bottom: 2rem; max-width: 4cm; height: auto;">
    <div style="justify-content: space-between; display: flex; "><span>Tiket No. {{ $partisipan[0]->id }}</span><span style="font-weight: bold;">{{ $acara->title }}</span></div>   
    <div style="justify-content: space-between; display: flex; text-align: end"><span>___</span><span>{{ $acara->subtitle }}</span></div>   
    
    <hr size="3" color="black" style="border-top: dotted 1px; margin: 1rem auto;">
    
    <div style="justify-content: space-between; display: flex; "><span>Peserta </span><span style="font-weight: bold;">{{ $partisipan[0]->user->name }}</span></div>
    <div style="justify-content: space-between; display: flex; "><span>Kontak </span><span>{{ $partisipan[0]->user->phone }}</span></div>

    <hr size="3" color="black" style="border-top: dotted 1px; margin: 1rem auto;">
    
    <div style="justify-content: start; display: flex; font-weight: bold;"><span>Pembayaran </span></div>
    @foreach ($pembayaran as $bayar)
    <div style="justify-content: space-between; display: flex; "><span>{{ $bayar->date_payment }}</span><span>.</span></div>
    <div style="justify-content: space-between; display: flex; "><span>{{ $bayar->payment_method }}</span><span>Rp{{ Illuminate\Support\Number::format($bayar->nominal, locale: 'de') }}</span></div>
    @endforeach
    
    <hr size="3" color="black" style="border-top: dotted 1px; margin: 1rem auto;">

    <div style="display: flex; justify-content: center; align-items: center; margin-top: 2rem;">
        <div>{!! QrCode::size(120)->generate(url($url)) !!}</div>
    </div>

 <script type = 'text/javascript'>  
    window.onload = function(){ window.print(); }
</script>
</body>
</html>