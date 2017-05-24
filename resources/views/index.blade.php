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
    <body>
        <div class="wrapper">
            <section class="header">
                <div class="holder">
                    <div class="header-top">
                        <img src="{{ URL::asset('public/images/logo.png') }}" alt="logo" class="logo">
                        <div class="header-right">
                            <ul>
                                <li class="active"><a href="{{ url('/?lang=EN') }}">English</a></li>
                                <li class="arabic"><a href="{{ url('/') }}">العربية</a></li>
                            </ul>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="head-inner">
                        <div class="text-area">
                            <h3>Khair Keys</h3>
                            <p>Find your favorite events,<br> Add your Events and activities,<br> and share it to all the Emirates and cities.  </p>
                            <ul class="store-links">
                                <li><a href="#"><img src="{{ URL::asset('public/images/appstore.png') }}"></a></li>
                                <li class="play-store"><a href="#"><img src="{{ URL::asset('public/images/playstore.png') }}"></a></li>
                            </ul>
                            <a href="#home" class="arrow"></a>
                        </div>
                        <img src="{{ URL::asset('public/images/img1.png') }}" alt="img1" class="head-img">
                    </div>
                </div>
            </section>
            <section class="home" id="home">
                <div class="left">
                    <div class="img-wrap">
                        <img src="{{ URL::asset('public/images/img2.png') }}" alt="image 2">
                    </div>
                </div>
                <div class="right">
                    <div class="text-area">
                        <h2 class="sect-head">Home Screen</h2>
                        <p>Find all the purposeful and good events with activities around you, see what's upcoming what's ongoing, Also see the featured events in UAE. And get your own platform to share your own events to all around UAE.</p>
                        <p>Add to favorite, or share any event with your family and friends through your social media accounts.</p>
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
                        <img src="{{ URL::asset('public/images/img3.png') }}" alt="image 2">
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
                <div class="holder">
                    <div class="left">
                        <div class="text-area">
                            <div class="about">
                                <h4>KK Application</h4>
                                <p>Copyright &copy; 2017 - Khair keys app and website are powered by Synergistics FZ LLC
                                    Khair keys App is an application under Apple store and Android play store platform that helps users to search for purposeful Events, activities, lectures, exhibitions, galleries, shows and all the up showing activities for all UAE citizens residents and visitors.
                                </p>
                            </div>
                            <div class="contact-area">
                                <div class="contact-me">
                                    <h4>contact me</h4>
                                    <!-- To register or For more information please contact us -->
                                    <address>Abu Dhabi - Al Ain city</address>
                                    <span class="phone">Phone: +971506839060</span>
                                    <span class="email">Email: <a href="mailto:info@khairkeys.com"> info@khairkeys.com</a></span>
                                </div>
                                <div class="contact-me">
                                    <h4>follow me on</h4>
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
                    <li><a class="fancybox" href="#aboutus">About Us</a></li>
                    <li><a class="fancybox" href="#faq">FAQ</a></li>
                    <li><a class="fancybox" href="#privacypolicy">Terms of Use</a></li>
                </ul>
            </section> 

            <div id="aboutus" class="textbox">
                <h1>About Us</h1>
                <h3>Information we collect</h3>
                <p>       We display the on-coming and the available events around the UAE to provide better services to all of our users – from figuring out basic stuff like which language you speak, your location, which category you choose and your favorite type of activity.    </p>

                <h3>Information we get from your use of our services.</h3>
                <p>We collect a full details about the events and activities that you provide and how to share them with your links, like when you click on an event, it appears your main website that displays the whole advertisings.</p>

                <h3> Policy of sharing event</h3>
                <p>Khair  keys app displays all the <strong><u>purposeful </u></strong>events  and activities, which adds to the users and community for social benefits. </p>
                <p>We  are not responsible of any mistake the USERS make after posting the event, and  if there are many complains on a verified USER, the user will get Automatically  blocked with all the events the user previously posted. And we're not  responsible of any loss as long as the user have the option to edit and  clarify.</p>

                <p><strong>Mistakes  examples:</strong></p>
                <ol>
                    <li>Posting an event with wrong date and time</li>
                    <li>Posting an event which is actually paid as free event</li>
                    <li>Posting an event with wrong details and location</li>
                </ol>

                <h3>Information security </h3>
                <p>We work hard to protect our app and site from the fake users, and our users from unauthorized access to or unauthorized alteration, disclosure or destruction of information we hold. In particular: </p>
                <ul>
                    <li>We encrypt many of our service using SSL. </li>
                    <li>We offer you a verification letter you receive on your E-mail once you sign up.  </li>
                    <li>We review our information collection, storage and processing practices, including physical security measures, to guard against unauthorized access to systems. </li>
                    <li>We restrict access to personal information to Google employees, contractors and agents who need to know that information in order to process it for us, and who are subject to strict contractual confidentiality obligations and may be disciplined or terminated if they fail to meet these obligations.</li>
                </ul>

            </div>
            <div id="faq" class="textbox">
                <h1>FAQ’s</h1>
                <h3>What’s KKY APP?</h3>
                <p>It's an application that displays all the purposeful events around you, and gives you a space to share your own events as well.</p>

                <h3>WHY KKY APP?</h3>
                <p>Because this app provides different kind of events which is for the community sake, and happiness, something will add something new to their daily lifestyle. By displaying what useful more than entertaining activities.</p>

                <h3>Where can I apply as a member?</h3>
                <p>Sign up on our app as a corporate, read the rules and regulations and we will get to you as soon as possible to give you a user name and password.</p>

                <h3>Where can I complain or add a suggestion or ask for info?</h3>
                <p><a href="mailto:info@khairkey.com">info@khairkey.com</a></p>

                <h3>What are the privacy and policy of the app?</h3>
                <p><a class="fancybox" href="#privacypolicy">Terms of Use</a></p>

                <h3>How can I verify my account?</h3>
                <p>Once you sign-up you'll receive an E-mail, click on the link then go back to the app.</p>

                <h3>Can I post any ongoing event?</h3>
                <p>Yes, if it's purposeful for you and the others.</p>

                <h3>How can I edit my event details?</h3>
                <p>From your profile settings up</p>

                <h3>How to change my profile language?</h3>
                <p>By clicking on your profile, then choosing the option ' settings'.</p>

                <h3>What's your website?</h3>
                <p><a href="http://www.khairkeys.com">Khairkeys.com</a></p>
            </div>
            <div id="privacypolicy" class="textbox">
                <h1>Terms of Use</h1>
                <p>Please read these Terms and conditions of use which you may make use of like our website and Application , khairkeys, to improve  accessing, browsing, or registering on our services</p>

                <h3>Personal information</h3>
                <p>Once a user fills in the sign-up form, we use the personal information only if there's a problem in logging in, and if any activated user posted and advertisement of any event we'll use the personal information to contact them. We have the right to disable any user identification code or password, whether chosen by you or allocated by us, at any time, if in our reasonable opinion you have failed to comply with any of the provisions of these terms of use.</p>

                <h3>Links</h3>
                <p>Our APP has a number of events and activities which are linked to different organizations and agencies. In most of the events, we link to private organizations with their permission. It is important for you to note that upon linking to another site from any event web link, you will automatically be on their page as a subject to the privacy policy of the new site you clicked on.</p>


                <h3>Cookies</h3>
                <p>We uses cookies to identify the web server which the user is utilizing, Cookies are a convenient way to carry information from one session on a website to another, or between sessions on related websites, without having to burden a server machine with massive amounts of data storage.</p>
            </div>

        </div>
    </body>
</html>
<script>
    (function (i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function () {
            (i[r].q = i[r].q || []).push(arguments)
        }, i[r].l = 1 * new Date();
        a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
    })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');
    ga('create', 'UA-98097150-1', 'auto');
    ga('send', 'pageview');
</script>