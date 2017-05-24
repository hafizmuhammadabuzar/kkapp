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
        <script type="text/javascript" src="{{ URL::asset('public/js/jquery.fancybox.pack.js?v=2.1.5')}}"></script>
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('public/css/jquery.fancybox.css?v=2.1.5')}}" media="screen" />
        <script type="text/javascript">
            $(document).ready(function () {
                $('.fancybox').fancybox();
            });
        </script>
    </head>
<body class="arb">
<div class="wrapper">
	<section class="header">
		<div class="holder">
			<div class="header-top">
				<img src="{{ URL::asset('public/images/logo.png') }}" alt="logo" class="logo">
				<div class="header-right">
					<ul>
						<li><a href="{{url('/?lang=EN')}}">English</a></li>
						<li class="arabic active"><a href="{{url('/AR')}}">العربية</a></li>
					</ul>
				</div>
				<div class="clear"></div>
			</div>
			<div class="head-inner">
				<div class="text-area">
					<h3>مفاتيح الخير</h3>
					<p>استعرض الفعاليات الهادفة من حولك، واعرض فعالياتك الخاصة بك وشاركها</p>
					<ul class="store-links">
						<li><a href="#"><img src="{{ URL::asset('public/images/appstore.png') }}"></a></li>
						<li class="play-store"><a href="#"><img src="{{ URL::asset('public/images/playstore.png') }}"></a></li>
					</ul>
					<a href="#home" class="arrow"></a>
				</div>
				<img src="{{ URL::asset('public/images/img1-arb.png') }}" alt="img1" class="head-img">
			</div>
		</div>
	</section>
	<section class="home" id="home">
		<div class="left">
			<div class="img-wrap">
				<img src="{{ URL::asset('public/images/img2-arb.png') }}" alt="image 2">
			</div>
		</div>
		<div class="right">
			<div class="text-area">
				<h2 class="sect-head">الرئيسية</h2>
				<p>استعرض جميع الفعاليات الهادفة والأنشطة المختلفة المقامة من حولك أينما كنت في الإمارات، استعرض الفعاليات الأكثر مشاركة والفعاليات المميزة في القائمة. كما يمكنك أيضاً التسجيل معنا للحصول على منصتك الخاصة بك لعرض فعالياتك الخاصة ومشاركتها لتصل إلى أكبر عدد من الجمهور..</p>
				<a href="#search" class="arrow"></a>
			</div>
		</div>
	</section>
	<section class="search" id="search">
		<div class="left">
			<div class="text-area">
				<h2 class="sect-head">الخدمات الذكية</h2>
				<p>يتيح التطبيق خيارات ذكية عدة، في البحث يمكنك اختيار فعالياتك المحددة حسب تصنيفات دقيقة تستطيع إرضاءك بالنتائج، كما يمكنك أيضاً إضافة فعالياتك وتصنيفها حسب الخيارات الذكية، لتصل إلى المستهدفين بطريقة صحيحة وبشكل صحيح.</p>
				<a href="#stores" class="arrow"></a>
			</div>
		</div>
		<div class="right">
			<div class="img-wrap">
				<img src="{{ URL::asset('public/images/img3-arb.png') }}" alt="image 2">
			</div>
		</div>
	</section>
	<section class="stores" id="stores">
		<div class="left">
			<div class="text-area">
				<h2 class="sect-head">appstore</h2>
				<p>حمل التطبيق من متجر أبل و متجر بلاي واستمتع باستعراض فعالياتنا من حولك</p>
				<a href="#" class="btn-appstore">appstore</a>
				<a href="#footer" class="arrow"></a>
			</div>
		</div>
		<div class="right">
			<div class="text-area">
				<h2 class="sect-head">playstore</h2>
				<p>حمل التطبيق من متجر أبل و متجر بلاي واستمتع باستعراض فعالياتنا من حولك</p>
				<a href="#" class="btn-playstore">playstore</a>
				<div class="arrow-wrap">
					<a href="#footer" class="arrow"></a>
				</div>
			</div>
		</div>
	</section>
	<section class="footer" id="footer">
		<div class="holder">
			<div class="left">
			<div class="text-area">
				<div class="about">
					<h4>تطبيق مفاتيح الخير</h4>
					<p>حقوق النشر محفوظة 2017- تطبيق وموقع مفاتيح الخير بواسطة Synergistics FZ LLC .
تطبيق مفاتيح الخير مدرج في منصة Apple store  و Android play store، يقوم التطبيق بعرض الفعاليات والأنشطة الهادفة كالمحاضرات بأنواعها، والمهرجانات والمعارض والبرامج المختلفة، المتاحة والقادمة لجميع المقيمين والزائرين.</p>
				</div>
				<div class="contact-area">
					<div class="contact-me">
						<h4>للتواصل معنا</h4>
                        <!-- للتسجيل أو للمزيد من المعلومات يرجى التواصل معنا عبر -->
						<address>Abu Dhabi - Al Ain city</address>
						<span class="phone">Phone: +971506839060</span>
						<span class="email">Email: <a href="mailto:info@khairkeys.com"> info@khairkeys.com</a></span>
					</div>
					<div class="contact-me">
						<h4>تابعونا على وسائل التواصل التالية</h4>
						<ul class="social-links">
							<li><a href="https://www.facebook.com/khair.keys" target="_blank">facebook</a></li>
							<li class="twitter"><a href="https://twitter.com/khairkeys" target="_blank">twitter</a></li>
							<li class="instagram"><a href="https://www.instagram.com/khairkeys/" target="_blank">instagram</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
			<div class="right">
			<div class="img-wrap">
				<img src="{{ URL::asset('public/images/img4.png') }}" alt="img4">
			</div>
		</div>
		</div>
	</section>
	<section class="footerbottom">
		<ul>
        	<li><a class="fancybox" href="#aboutus">معلومات عنا</a></li>
			<li><a class="fancybox" href="#faq">أسئلة وأجوبة</a></li>
			<li><a class="fancybox" href="#privacypolicy">شروط الإستخدام</a></li>
		</ul>
	</section> 
    
<div id="aboutus" class="textbox">    
    <h1>معلومات عنا</h1>
    <h3>البيانات والعلومات التي نقوم بجمعها</h3>
    <p> نحن نعرض كل ما هو قادم ومتاح من عروض وفعاليات وأنشطة مختلفة في دولة الإمارات العربية المتحدة، لنقدم أفضل الخدمات وبشكل أفضل للمستخدمين، وذلك من خلال استخدام بيانات بسيطة مثل اللغة والموقع، ونوع الفعالية وتصنيفك الخاص للفعالية أو النشاط.</p>
    
    <h3> المعلومات التي نجمعها من خلال فائدة المستخدم على خدماتنا</h3>
    <p>نقوم بجمع بيانات كاملة عن الفعالية أو النشاط من خلالكم، وطريقة المشاركة بها من خلال الروابط الخاصة بكم، مثلاً عند الضغط على فعاليةٍ ما يبرز الرابط المباشر إلى موقعكم الرئيسي الذي يعرض جميع فعالياتكم.</p>
    
    <h3>سياسة الاعلان عن الفعاليات</h3>
    <p>يعرض تطبيق مفاتيح الخير جميع الفعاليات والأنشطة الهادفة فقط التي تضيف قيمة وفائدة للمجتمع.</p>
      <p>- نحن غير مسؤولون عن أي خطأ يرتكبه المستخدمون للتطبيق بعد نشر الفعالية، وإن كانت هناك شكاوى عدة قائمة على المستخدم، فإن المستخدم يُحظر تلقائياً، وتحذف جميع فعالياته السابقة، ونحن غير مسؤولون لأي خسارة يتسبب فيها المستخدم بما أن لديه خيار التحرير والتعديل والشرح والتوضيح.</p>
      
      <p><strong>المشكلات التي قد يقع فيها المستخدم :</strong></p>
      <ol>
      	<li>مشاركة فعالية بتاريخ أو توقيت خاطئ</li>
        <li>الإعلان عن الفعالية بإنها مجانية وهي مدفوعة</li>
        <li>الإعلان عن الفعالية بعنوان خاطئ أو بيانات أخرى خاطئة</li>
      </ol>
      
	<h3>أمن المعلومات</h3>
    <p>نعمل جاهدين على حماية التطبيق من الحسابات الوهمية، والحسابات التي تسعى إلى إتلاف البيانات الموجودة في التطبيق والموقع ويشمل ذلك على وجه الخصوص :</p>
    <ul>
    	<li>أجراء تشفير لخدماتنا باستخدم طبيقة المقابس الآمنة</li>
        <li>توفير التحقق من الحساب من خلال رسالة التأكيد</li>
        <li>تجري مراجعة لإجراءات النشر والمشاركة</li>
        <li>يقتصر الوصول للمعلومات إلى المعلومات الشخصية على موظفي التطبيق الإداريين ووكلاء التطبيق الذين يحتاجون إلى معرفة هذه المعلومات ومعالجتها، والذين لديهم التزامات تعاقدية صارمة بشأن السرية بحيث يعرضهم ذلك إلى إيقاف حساباتهم.</li>
    </ul>   
    
</div>
<div id="faq" class="textbox">
	<h1>أسئلة وأجوبة</h1>
    <h3>ماهو تطبيق مفاتيح الخير ؟</h3>
    <p>تطبيق مفاتيح الخير عبارة عن منصة لعرض جميع الفعاليات الهادفة ومختلف الأنشطة من حولك، وتتيح لك منصة خاصة لعرض فعالياتك ومشاركتها.</p>
    
    <h3>ماذا تطبيق مفاتيح الخير</h3>
    <p>التطبيق يعرض لك فعاليات وأنشطة مختلفة، بأنواع جديدة، وهو التطبيق الوحيد الذي يشمل جميع الفعاليات الهادفة في الدولة على منصة واحدة، وهي المنصة الوحيدة التي تستهدف الأفراد بجميع شرائحهم لتحسين مستوى السلوك الحياتي في المجتمع. من خلال عرض الفعاليات التي تنمي الفكر، وتطور المهارات وما إلى ذلك.</p>
    
    <h3>كيف يمكنني التسجيل كمستخدم تابع لجهة أو مؤسسة؟</h3>
    <p>قم بالتسجيل من خلال موقعنا الإلكتروني، أو عن طريق التطبيق، من خلال ملء بيانات الجهة أو المؤسسة، وسنعاود الإتصال بك، لمنحك ( اسم مستخدم معتمد و كلمة مرور )</p>
    
	<h3>أين يمكنني أن أقدم على اقتراح أو شكوى أو أطلب معلومة ؟</h3>
    <p><a href="mailto:info@khairkey.com">info@khairkey.com</a></p>
    
    <h3>ماهي سياسات وخصوصيات التطبيق والموقع.</h3>
    <p><a class="fancybox" href="#privacypolicy">شروط الإستخدام</a></p>
    
    <h3>كيف يمكنني تأكيد حسابي ؟</h3>
    <p>بمجرد أن تقوم بالتسجيل معنا، سوف تستلم بريد الالكتروني برابط التفعيل، انقر عليه.</p>
    
    <h3>هل يمكنني أن أضيف أي فعالية ؟</h3>
    <p>نعم، إن كانت فعالية هادفة أو نشاط هادف.</p>
    
    <h3>كيف يمكنني تعديل فعالياتي عن طريق حسابي ؟</h3>
    <p>عن طريق الضبط </p>
    
    <h3>كيف يمكنني تغيير اللغة ؟</h3>
    <p>ن خلال اعدادات الحساب .</p>
    
    <h3>ما هو موقع الويب الخاص بك؟</h3>
    <p><a href="http://www.khairkeys.com">Khairkeys.com</a></p>
</div>
<div id="privacypolicy" class="textbox">
	<h1>شروط الإستخدام</h1>
    <p>يرجى قراءة شروط الاستخدام ، التي تسهم في تحسين موقعنا وتطبيقنا للمستخدم في البحث والتسجيل والمشاركة. </p>
    
    <h3>لبيانات الشخصية</h3>
    <p>نقوم بإستخدام البيانات المستخدمة عند التسجيل في حاللة استرجاع الحساب، وللاتصال بالمنظمين والمعلنين لتتحقق من صحة بيانات الفعاليات والأنشطة المعلن عنها من قبلهم. ولدينا الحق في إيقاف أي حساب في حالة مخالفة أي من المذكور. </p>
    
    <h3>الروابط</h3>
    <p>يشمل التطبيق عدد كبير من الفعاليات والأنشطة المختلفة والتي تكون مرتبطة بمختلف الشركات والجهات المنظمة، فمن حق المستخدم أن يعلم أنه بمجرد الضغط على الرابط المتاح في الفعالية فإنه ينتقل تلقائياً إلى الصفحة الرئيسية لمنظم الفعالية ويكون غير معرف لديهم.</p>
      
      
	<h3>الكوكيز</h3>
    <p>نحن نستخدم ملفات تعريف الارتباط لتحديد خادم الويب الذي يستخدمه المستخدم، الكوكيز هي وسيلة مريحة لنقل المعلومات من جلسة واحدة على موقع إلى آخر، أو بين جلسات على المواقع ذات الصلة، دون الحاجة إلى عبء جهاز الخادم مع كميات هائلة من البيانات</p>
</div>
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