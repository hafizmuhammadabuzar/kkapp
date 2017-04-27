<!DOCTYPE html>
<html>
<head>
	<title>Khair Keys</title>
	<meta charset="utf-8">
	<meta name="author" content="Khair Keys">
	<meta name="format-detection" content="telephone=no"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
	<link rel="shortcut icon" href="{{ URL::asset('public/images/favicon.png')}}">
	<link rel="stylesheet" href="{{ URL::asset('public/css/style.css')}}">
	<script type="text/javascript" src="{{ URL::asset('public/js/jquery-1.11.2.min.js')}}"></script>
	<script type="text/javascript" src="{{ URL::asset('public/js/all.js')}}"></script>
</head>
<body>
<div class="wrapper">
	<section class="header">
		<div class="holder">
			<img src="{{ URL::asset('public/images/logo.png')}}" alt="logo" class="logo">
			<div class="head-inner">
				<div class="text-area">
					<h3>Khair Keys</h3>
					<p>Add your Event and Share <br>with Others</p>
					<ul class="store-links">
						<li><a href="#"><img src="{{ URL::asset('public/images/appstore.png')}}"></a></li>
						<li class="play-store"><a href="#"><img src="{{ URL::asset('public/images/playstore.png')}}"></a></li>
					</ul>
					<a href="#home" class="arrow"></a>
				</div>
				<img src="{{ URL::asset('public/images/img1.png')}}" alt="img1" class="head-img">
			</div>
		</div>
	</section>
	<section class="home" id="home">
		<div class="left">
			<div class="img-wrap">
				<img src="{{ URL::asset('public/images/img2.png')}}" alt="image 2">
			</div>
		</div>
		<div class="right">
			<div class="text-area">
				<h2 class="sect-head">Home Screen</h2>
				<p>Find events happening near you, see the featured events at the top, or add an event if you like to share with the world.</p>
				<p>Add to favourite, or share any event with your family and friends.</p>
				<a href="#search" class="arrow"></a>
			</div>
		</div>
	</section>
	<section class="search" id="search">
		<div class="left">
			<div class="text-area">
				<h2 class="sect-head">Advance search</h2>
				<p>Use filters for searching, you can find specific event which you are looking for.</p>
				<p>Search by category, type , language, location and many more advance searching features.</p>
				<a href="#stores" class="arrow"></a>
			</div>
		</div>
		<div class="right">
			<div class="img-wrap">
				<img src="{{ URL::asset('public/images/img3.png')}}" alt="image 2">
			</div>
		</div>
	</section>
	<section class="stores" id="stores">
		<div class="left">
			<div class="text-area">
				<h2 class="sect-head">appstore</h2>
				<p>Download now and start adding events now on iPhone</p>
				<a href="#" class="btn-appstore">appstore</a>
				<a href="#footer" class="arrow"></a>
			</div>
		</div>
		<div class="right">
			<div class="text-area">
				<h2 class="sect-head">playstore</h2>
				<p>Download now and start adding events now on Android</p>
				<a href="#" class="btn-playstore">playstore</a>
				<div class="arrow-wrap">
					<a href="#footer" class="arrow"></a>
				</div>
			</div>
		</div>
	</section>
	<section class="footer" id="footer">
		<div class="left">
			<div class="text-area">
				<div class="about">
					<h4>KK Application</h4>
					<p>Mauris ornare lobortis arcu, at sodales dolor finibus nec. In convallis risus ligula, bibendum sagittis ante cursus vitae. Donec ornare, dui nec sagittis imperdiet, nunc eros dignissim augue, ut aliquam nibh felis sit amet leo. Vivamus eu tincidunt tellus. Vestibulum quis condimentum lectus, nec aliquet lectus. Duis sollicitudin facilisis dui ac aliquet. Phasellus tempor sollicitudin augue, id hendrerit enim gravida non. Integer consequat vel arcu quis dictum. Vestibulum viverra commodo nunc eu pellentesque. Phasellus in scelerisque elit, eu ultricies mauris.</p>
					<p>&copy;2015. all rights reserved.</p>
				</div>
				<div class="contact-area">
					<div class="contact-me">
						<h4>contact me</h4>
						<address>1242 Crestview Terrace <br> Artesia Wells, TX 78001</address>
						<span class="phone">Phone: 830-676-7974</span>
						<span class="email">Email: halo@sitename.com</span>
					</div>
					<div class="contact-me">
						<h4>follow me on</h4>
						<ul class="social-links">
							<li><a href="#">facebook</a></li>
							<li class="twitter"><a href="#">twitter</a></li>
							<li class="instagram"><a href="#">instagram</a></li>
							<li class="spotify"><a href="#">spotify</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div class="right">
			<div class="img-wrap">
				<img src="{{ URL::asset('public/images/img4.png')}}" alt="img4">
			</div>
		</div>
	</section>
</div>
</body>
</html>
<script>
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

	ga('create', 'UA-98097150-1', 'auto');
	ga('send', 'pageview');

</script>