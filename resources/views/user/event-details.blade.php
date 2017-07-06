@extends('user.layout.master')

@section('content')

<div class="main">
    <div class="container">
        <div class="row">
            <div class="head head-on">
                <h4>Add Event اضف فعالية</h4>
            </div>
            <div class="add-event">
                @include('..partials/errors')
{{$uri_segment}}
                @if($uri_segment == 'add-event' || $uri_segment == 'duplicate-event')
                <form action="{{url('user/save-event')}}" method="post" enctype="multipart/form-data" id="form-event">
                    @elseif($uri_segment == 'edit-event')
                    <form action="{{url('user/update-event')}}" method="post" enctype="multipart/form-data" id="form-event">
                        @endif
                        <div class="field-wrap type clearfix">
                            <div class="left">
                                <h4>Type*  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    نوع</h4>
                                <?php $type_id = (isset($event)) ? explode(',', $event->type_id) : explode(',', old('type')); ?>
                                @foreach($types as $key => $tp)
                                <div class="radio-wrap">
                                    <input type="checkbox" id="event_type_<?php echo $key; ?>" name="type[]" value="{{$tp->id}}"<?php if (in_array($tp->id, $type_id)) echo 'checked="checked"'; ?>>
                                    <label for="event_type_<?php echo $key; ?>">{{$tp->english.'&nbsp;&nbsp;&nbsp;&nbsp; '.$tp->arabic}}</label>
                                </div>
                                @endforeach
                            </div>
                            <div class="right left-right">
                                <h4>Category* &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    الفئة</h4>
                                <?php $cat_id = (isset($event)) ? explode(',', $event->category_id) : explode(',', old('category')); ?>
                                @foreach($categories as $key => $cat)
                                <div class="radio-wrap">
                                    <input type="checkbox" id="event_cat_<?php echo $key; ?>" name="category[]" value="{{$cat->id}}" <?php if (in_array($cat->id, $cat_id)) echo 'checked="checked"'; ?>>
                                    <label for="event_cat_<?php echo $key; ?>">{{$cat->english.'&nbsp;&nbsp;&nbsp;&nbsp; '.$cat->arabic}}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="field-wrap clearfix">
                            <div class="left">
                                <label for="keyword">Keyword  الكلمات المفتاحية</label>
                                <?php $keyword = (isset($event)) ? $event->keyword : old('keyword'); ?>
                                <input type="text" id="keyword" name="keyword" value="{{$keyword}}" data-role="tagsinput">
                            </div>
                            <div class="right left-right">
                                <div class="label-wrap">
                                    <label for="url">Web link</label>
                                    <label for="url">رابط موقع</label>
                                    <?php $url = (isset($event)) ? $event->weblink : old('url'); ?>
                                </div>
                                <input type="text" id="url" name="url" value="{{$url}}">
                            </div>
                        </div>
                        <div class="field-wrap clearfix">
                            <div class="left">
                                <label for="event-name">Event Name*</label>
                                <?php $eng_name = (isset($event)) ? $event->eng_name : old('event_name'); ?>
                                <input type="text" id="event-name" name="event_name" value="{{$eng_name}}" required="required">
                            </div>
                            <div class="right">
                                <label for="event-name-ar">اسم الحدث*</label>
                                <?php $ar_name = (isset($event)) ? $event->ar_name : old('event_name_ar'); ?>
                                <input type="text" id="event-name-ar" name="event_name_ar" value="{{$ar_name}}" required="required">
                            </div>
                        </div>
                        <div class="field-wrap clearfix">
                            <div class="left">
                                <label for="event-company">Organizer/Company Name*</label>
                                <?php $comp_name_eng = (isset($event)) ? $event->eng_company_name : old('event_company'); ?>
                                <input type="text" id="event-company" name="event_company" value="{{$comp_name_eng}}" required="required">
                            </div>
                            <div class="right">
                                <label for="event-company-ar">اسم الشركة*</label>
                                <?php $comp_name_ar = (isset($event)) ? $event->ar_company_name : old('event_company_ar'); ?>
                                <input type="text" id="event-company-ar" name="event_company_ar" value="{{$comp_name_ar}}" required="required">
                            </div>
                        </div>
                        <div class="field-wrap clearfix">
                            <div class="left">
                                <div class="label-wrap">
                                    <label for="phone">Phone</label>
                                    <?php $phone = (isset($event)) ? $event->phone : old('phone'); ?>
                                    <label for="phone">هاتف</label>
                                </div>
                                <input type="text" id="phone" name="phone" value="{{$phone}}">
                            </div>
                            <div class="right left-right">
                                <div class="label-wrap">
                                    <label for="email">Email</label>
                                    <label for="email">البريد الإلكتروني</label>
                                    <?php $email = (isset($event)) ? $event->email : old('email'); ?>
                                </div>
                                <input type="text" id="email" name="email" value="{{$email}}">
                            </div>
                        </div>
                        <div class="field-wrap clearfix">
                            <?php $all_day = (isset($event)) ? $event->all_day : ''; ?>
                            <div class="left">
                                <div class="all-day right clearfix left-right">
                                    <h4>All day event  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    كل حدث اليوم</h4>
                                    <div class="radio-wrap">
                                        <?php $all_day = (isset($event)) ? $event->all_day : old('all_day'); ?>
                                        <input type="checkbox" id="all_day" name="all_day" value="1" <?php if ($all_day == 1) echo 'checked="checked"' ?>>
                                        <label for="yes">Yes   &nbsp;&nbsp;&nbsp;  نعم </label>
                                    </div>
                                </div>
                            </div>
                            <div class="right cal event_dates">
                                <div class="start-date">
                                    <span>تاريخ البدء *Start Date</span>
                                    <?php
                                    if (isset($event)) {
                                        if ($event->all_day != 1) {
                                            $s_time = date('h:i A', strtotime($event->start_date));
                                            $e_time = date('h:i A', strtotime($event->end_date));
                                        }
                                        else{
                                            $s_time = '';
                                            $e_time = '';
                                        }
                                        if (!empty($event->start_date)) {
                                            $s_date = date('d-m-Y', strtotime($event->start_date));
                                            $e_date = date('d-m-Y', strtotime($event->end_date));
                                        } else {
                                            $s_date = '';
                                            $s_time = '';
                                            $e_date = '';
                                            $e_time = '';
                                        }
                                    } else {
                                        if (!empty(old('start_date'))) {
                                            $s_date = date('d-m-Y', strtotime(old('start_date')));
                                            $s_time = date('h:i', strtotime(old('start_date')));
                                            $e_date = date('d-m-Y', strtotime(old('end_date')));
                                            $e_time = date('h:i', strtotime(old('end_date')));
                                        } else {
                                            $s_date = '';
                                            $s_time = '';
                                            $e_date = '';
                                            $e_time = '';
                                        }
                                    }
                                    ?>
                                    <input type="text" placeholder="HH:MM AM" id="start_time" name="start_time" value="{{$s_time}}" required="required" pattern="^([0-1]?[0-9]|2[0-4]):([0-5][0-9])(:[0-5][0-9])?$" class="timepicker start-time" <?php
                                    if ($all_day) {
                                        echo 'disabled="disabled"';
                                    }
                                    ?>>
                                    <input type="text" placeholder="date" id="start_date" name="start_date" value="{{$s_date}}" class="start-input" required="required">

                                </div>
                                <div class="end-date">
                                    <span>تاريخ الانتهاء *End Date</span>
                                    <input type="text" placeholder="HH:MM AM" id="end_time" name="end_time" value="{{$e_time}}" required="required" pattern="^([0-1]?[0-9]|2[0-4]):([0-5][0-9])(:[0-5][0-9])?$" class="timepicker end-time" <?php
                                    if ($all_day) {
                                        echo 'disabled="disabled"';
                                    }
                                    ?>>
                                    <input type="text" placeholder="date" id="end_date" name="end_date" value="{{$e_date}}" class="end-input" required="required">
                                </div>
                            </div>
                        </div>
                        <div class="field-wrap clearfix">
                            <div class="left">
                                <label for="event-desc">Description</label>
                                <?php $en_dsc = (isset($event)) ? $event->eng_description : old('eng_description'); ?>
                                <textarea rows="6" name="eng_description" id="event-desc">{{$en_dsc}}</textarea>
                            </div>
                            <div class="right">
                                <label for="event-desc-ar">وصف</label>
                                <?php $ar_dsc = (isset($event)) ? $event->ar_description : old('ar_description'); ?>
                                <textarea rows="6" name="ar_description" id="event-desc-ar">{{$ar_dsc}}</textarea>
                            </div>
                        </div>
                        <div class="field-wrap clearfix">
                            <div class="left">
                                <h4>Facebook URL</h4>
                                <?php $facebook = (isset($event)) ? $event->facebook : old('facebook'); ?>
                                <div class="social-wrap">
                                    <span class="social-text">https://www.facebook.com/</span>
                                    <input type="text" id="facebook" name="facebook" value="{{$facebook}}">
                                </div>
                            </div>
                            <div class="right left-right">
                                <h4>Twitter URL</h4>
                                <?php $twitter = (isset($event)) ? $event->twitter : old('twitter'); ?>
                                <div class="social-wrap">
                                    <span class="social-text">https://www.twitter.com/</span>
                                    <input type="text" id="twitter" name="twitter" value="{{$twitter}}">
                                </div>
                            </div>
                        </div>
                        <div class="field-wrap clearfix">
                            <div class="left">
                                <h4>Instagram URL</h4>
                                <?php $instagram = (isset($event)) ? $event->instagram : old('instagram'); ?>
                                <div class="social-wrap">
                                    <span class="social-text">https://www.instagram.com/</span>
                                    <input type="text" id="instagram" name="instagram" value="{{$instagram}}">
                                </div>
                            </div>
                        </div>
                        <div class="field-wrap clearfix">
                            <div class="left">
                                <div class="field-wrap pic-clone">
                                    <h4>Add Pictures - اضف صورة <span class="small">Max 4</span></h4>
                                    @if($uri_segment != 'event-detail')
                                    <a href="#" style="display: block; margin: 0 0 10px;"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add more اضف المزيد</a>
                                    <div id="pictures">
                                        <input type="file" name="picture[]" id="picture" />
                                        <i class="fa fa-times pic-remove" aria-hidden="true"></i>
                                    </div>
                                    @endif
                                </div>
                                <div class="field-wrap attach-clone">
                                    <h4>Attachments - المرفقات <span class="small">Max 3</span></h4>
                                    @if($uri_segment != 'event-detail')
                                    <a href="#" style="display: block; margin: 0 0 10px;"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add more اضف المزيد</a>
                                    <div id="attachments">
                                        <input type="file" name="attachment[]" id="attachment" />
                                        <i class="fa fa-times attch-remove" aria-hidden="true"></i>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="right left-right">
                                <h4>Event Locations: &nbsp;&nbsp;&nbsp;&nbsp; موقع الفعالية</h4>
                                @if($uri_segment != 'event-detail')
                                <a href="#add-location" class="add-location"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add Location اضف موقع الفعالية</a>
                                @endif
                                <?php
                                if ($uri_segment != 'event-detail') {
                                    $old_cities = explode('~', old('city'));
                                    $old_locations = explode('~', old('location'));
                                    $old_latlngs = explode('~', old('latlng'));
                                    $old_event_languages = (!empty(old('event_language'))) ? old('event_language') : array();

                                    if (isset($event)) {
                                        $old_event_languages = explode(',', $event->event_language);
                                    }
                                } else {
                                    $old_event_languages = explode(',', $event->event_language);
                                }

                                if (count($old_event_languages) == 0) {
                                    $old_event_languages = array('2');
                                }

                                $city = '';
                                $location = '';
                                $latlng = '';
                                ?>
                                <ul class="location-list">
                                    @if(!isset($event))
                                    @for ($loc = 1; $loc < count($old_cities); $loc++)
                                    <li>
                                        <a href="#" class="add-location location-data">{{$old_locations[$loc].' | '.$old_cities[$loc].' | '.$old_latlngs[$loc]}}</a>
                                        <?php if ($uri_segment != 'event-detail') { ?>
                                            <a href="#" class="link-remove fa fa-remove location-remove"></a>
                                        <?php } ?>
                                    </li>
                                    @endfor
                                    @else
                                    @foreach($event->locations as $loc)
                                    <?php
                                    $city .= '~' . $loc->city;
                                    $location .= '~' . $loc->location;
                                    $latlng .= '~' . $loc->latitude . ',' . $loc->longitude;
                                    ?>
                                    <li>
                                        <a href="#" class="add-location location-data">{{$loc->location.' | '.$loc->city.' | '.$loc->latitude.', '.$loc->longitude}}</a>
                                        <?php if ($uri_segment == 'edit-event') { ?>
                                            <a href="#" class="link-remove fa fa-remove location-remove"></a>
                                        <?php } ?>
                                    </li>
                                    @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>

                        @if($uri_segment != 'add-event')
                        <div class="field-wrap clearfix">
                            <label>Pictures</label>
                            <ul class="img-list">
                                @foreach($event->pictures as $pic)
                                <li>
                                    <img src="{{URL::asset('public/uploads/'.$pic->picture)}}" alt="image" id="pic">
                                    <?php if ($uri_segment == 'edit-event') { ?>
                                        <span class="remove-img"><i class="fa fa-remove picture-delete" data-event="{{'pictures,'.Crypt::encrypt($event->id).','.Crypt::encrypt($pic->id)}}"></i></span>
                                    <?php } ?>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <div class="field-wrap languages clearfix">
                            <div class="left">
                                <h4>Language of Event*  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    لغة الحدث</h4>
                                @foreach($languages as $lang)
                                <div class="radio-wrap">
                                    <input type="checkbox" id="event_language" name="event_language[]" value="{{$lang->id}}" <?php if (in_array($lang->id, $old_event_languages)) echo 'checked="checked"' ?>>
                                    <label for="english">{{$lang->title}}</label>
                                </div>
                                @endforeach
                            </div>
                            <div class="right left-right">
                                <h4>Kids/Disabled  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    أصحاب الهمم /أطفال </h4>
                                <?php $kids = (isset($event)) ? $event->is_kids : old('kids'); ?>
                                <div class="radio-wrap">
                                    <input type="checkbox" id="kids" name="kids" value="1" <?php if ($kids == '1') echo 'checked="checked"' ?>>
                                    <label for="kids">Kids أطفال</label>
                                </div>
                                <?php $disable = (isset($event)) ? $event->is_disabled : old('disable'); ?>
                                <div class="radio-wrap">
                                    <input type="checkbox" id="disable" name="disable" value="1" <?php if ($disable == '1') echo 'checked="checked"' ?>>
                                    <label for="disable">Disable أصحاب الهمم</label>
                                </div>
                            </div>
                        </div>
                        @if($uri_segment != 'add-event')
                        <div class="field-wrap clearfix">
                            <p>Attachment</p>
                            <ul class="img-list">
                                @foreach($event->attachments as $attch)
                                <li>
                                    <img src="{{URL::asset('public/uploads/'.$attch->picture)}}" alt="image" id="attch_pic">
                                    <?php if ($uri_segment != 'event-detail') { ?>
                                        <span class="remove-img"><i class="fa fa-remove picture-delete" data-event="{{'attachments,'.Crypt::encrypt($event->id).','.Crypt::encrypt($attch->id)}}"></i></span>
                                    <?php } ?>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <div class="field-wrap clearfix">
                            <div class="left">
                                <h4>Venue*  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    المكان مخصص</h4>
                                <?php $venue = (isset($event)) ? $event->venue : old('venue'); ?>
                                <div class="inner-row">
                                    <div class="radio-wrap">
                                        <input type="radio" id="men" name="venue" value="men" <?php if ($venue == 'men') echo 'checked="checked"' ?> required="required">
                                        <label for="men">Men   &nbsp;&nbsp;&nbsp;  للذكور فقط</label>
                                    </div>
                                    <div class="radio-wrap">
                                        <input type="radio" id="women" name="venue" value="women" <?php if ($venue == 'women') echo 'checked="checked"' ?>>
                                        <label for="women">Women   &nbsp;&nbsp;&nbsp;  للنساء فقط</label>
                                    </div>
                                    <div class="radio-wrap">
                                        <input type="radio" id="separate" name="venue" value="separate" <?php if ($venue == 'separate') echo 'checked="checked"' ?>>
                                        <label for="separate">Separate Sitting   &nbsp;&nbsp;&nbsp;  مقاعد منفصلة</label>
                                    </div>
                                    <div class="radio-wrap">
                                        <input type="radio" id="public" name="venue" value="public" <?php if ($venue == 'public' || $venue == '' && $venue != 'men' && $venue != 'women' && $venue != 'separate') echo 'checked="checked"' ?>>
                                        <label for="public">Public   &nbsp;&nbsp;&nbsp;  الدعوة عامة</label>
                                    </div>
                                </div>
                            </div>
                            <div class="right left-right">
                                <h4>Paid/Free event* &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    مجاني غير مجاني</h4>
                                <div class="radio-wrap">
                                    <?php $fee = (isset($event)) ? $event->free_event : old('fee'); ?>
                                    <input type="radio" id="paid" name="fee" value="0" <?php if ($fee == '0') echo 'checked="checked"' ?> requried="required">
                                    <label for="paid">Paid   &nbsp;&nbsp;&nbsp;  غير مجاني</label>
                                </div>
                                <div class="radio-wrap">
                                    <?php $fee = (isset($event)) ? $event->free_event : old('fee'); ?>
                                    <input type="radio" id="free" name="fee" value="1" <?php if ($fee == '1' || $fee != '0') echo 'checked="checked"' ?> >
                                    <label for="free">Free   &nbsp;&nbsp;&nbsp;  مجاني</label>
                                </div>
                            </div>
                        </div>
                        @if($uri_segment != 'add-event')
                        <div class="field-wrap clearfix">
                            <h4>Featured  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    متميز</h4>
                            <?php $featured = (isset($event)) ? $event->is_featured : old('featured'); ?>
                            <div class="radio-wrap">
                                <input type="checkbox" id="featured" name="featured" value="1" <?php if ($featured == '1') echo 'checked="checked"' ?>>
                                <label for="featured">Yes نعم فعلا</label>
                            </div>
                        </div>
                        @endif
                        @if($uri_segment == 'edit-event' || $uri_segment == 'duplicate-event')
                        <input type="hidden" id="event_id" name="event_id" value="{{Crypt::encrypt($event->id)}}">
                        <input type="hidden" id="city" name="city" value="">
                        <input type="hidden" id="location" name="location" value="">
                        <input type="hidden" id="latlng" name="latlng" value="">
                        @endif
                        @if($uri_segment == 'add-event')
                        <input type="hidden" id="city" name="city" value="">
                        <input type="hidden" id="location" name="location" value="">
                        <input type="hidden" id="latlng" name="latlng" value="">
                        @endif

                        @if($uri_segment != 'event-detail')
                        <div class="field-wrap clearfix">
                            <div class="right">
                                <input type="submit" value="Submit" class="btn-submit">
                            </div>
                        </div>
                        @endif
                        <input type="hidden" id="uri" name="uri" value="{{$uri_segment}}">
                    </form>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')

<script type="text/javascript">
    $(document).ready(function () {

        if ($('#uri').val() == 'event-detail') {
            $(":input").attr("disabled", true);
        }

        if ($('#uri').val() == 'edit-event') {
            $("#start_time, #end_time").removeAttr('pattern');
        }

        if ($('#uri').val() == 'duplicate-event') {
            $("#all_day").attr('checked', false);
            $("#start_time").attr('disabled', false);
            $("#end_time").attr('disabled', false);
            $("#start_time").val('');
            $("#start_date").val('');
            $("#end_time").val('');
            $("#end_date").val('');
            $("#start_time").attr('type', 'time').focus();
        }

        $('#start_time').focus(function(){
            $(this).attr('type', 'time');
        });

        $('#end_time').focus(function(){
            $(this).attr('type', 'time');
        });

        $('#phone').keyup(function() {
            var $th = $(this);
            $th.val( $th.val().replace(/[^0-9]/g, function(str) { return ''; } ) );
        });

        $('.pic-clone a').click(function (e) {
            e.preventDefault();
            var pic_count = $('div[id^=pictures]').length + $('img[id^=pic]').length;
            if (pic_count < 4) {
                $("#pictures:last").clone().find("input:file").val("").end().appendTo(".pic-clone");
            }
        });
        $(document).on("click", ".pic-remove", function (e) {
            if ($('div[id^=pictures]').length > 1) {
                $(this).closest('#pictures').remove();
            } else {
                $('#picture').val('');
            }
        });

        $('.attach-clone a').click(function (e) {
            e.preventDefault();
            var attch_count = $('div[id^=attachments]').length + $('img[id^=attch_pic]').length;
            if (attch_count < 3) {
                $("#attachments:last").clone().find("input:file").val("").end().appendTo(".attach-clone");
            }
        });
        $(document).on("click", ".attch-remove", function (e) {
            if ($('div[id^=attachments]').length > 1) {
                $(this).closest('#attachments').remove();
            } else {
                $('#attachment').val('');
            }
        });

        $('.add-location').click(function () {
            initMap(24.4539, 54.3773);
        });

        $('#form-event').submit(function () {
            var start_date, start_time, end_date, end_time;
            start_date = $('#start_date').val();
            start_time = $('#start_time').val();
            end_date = $('#end_date').val();
            end_time = $('#end_time').val();
            start_time = start_time.substr(0, 5);
            end_time = end_time.substr(0, 5);
            if (start_time > end_time && start_date == end_date) {
                alert('Start time can not be greater than End time');
                $('#start_time').focus();
                return false;
            }
            start_date = start_date.split('-');
            end_date = end_date.split('-');
            var dateOne = new Date(start_date[2], start_date[1], start_date[0]);
            var dateTwo = new Date(end_date[2], end_date[1], end_date[0]);
            if (dateOne > dateTwo) {
                alert('Start date can not be greater than End date');
                $('#start_date').focus();
                return false;
            }
            if ($('input[name^=type]:checked').length == 0) {
                $('input[name^=type]').focus();
                return false;
            }
            if ($('input[name^=category]:checked').length == 0) {
                $('input[name^=category]').focus();
                return false;
            }
            if ($('input[name^=event_language]:checked').length == 0) {
                $('input[name^=event_language]').focus();
                return false;
            }

            var locs = $('.location-data').length;
            if (locs > 0) {
                var count = 1;
                $('.location-data').each(function () {
                    if (count <= locs) {
                        var data = $(this).text();
                        data = data.split('|');
                        var latlng = data[2].split(',');
                        $('#city').val($('#city').val() + '~' + data[1]);
                        $('#location').val($('#location').val() + '~' + data[0]);
                        $('#latlng').val($('#latlng').val() + '~' + latlng[0].replace('(', ' ') + ',' + latlng[1].replace(')', ' '));
                        count = (count + 1);
                    } else {
                        return false;
                    }
                });
            }
            return true;
//            $(this).submit();
//            }
        });

        $('.form-location').submit(function () {
            var city = $('#event-city').val();
            var city = city.split(',');
            $('.location-list').append('<li><a href="#" class="add-location location-data">' + $('#event-location').val() + ' | ' + city[0] + ' | ' + $('#event-latlngs').val() + '</a><a href="javascript:" class="link-remove fa fa-remove location-remove"></a></li>');
            $('.form-location')[0].reset();
            initMap(24.4539, 54.3773);
        });
        $('#event-city').change(function () {
            $('#event-location').val('');
            var city = $(this).val();
            var latlng = city.split(',');
            initMap(latlng[1], latlng[2]);
        });
        $(document).on("click", ".location-remove", function (e) {
            e.preventDefault();
            $(this).closest('li').remove();
        });
        $('#all_day').click(function () {
            if ($(this).is(':checked')) {
                $('#start_time').attr('disabled', true);
                $('#end_time').attr('disabled', true);
                $('#start_time').attr('required', false);
                $('#end_time').attr('required', false);
            } else {
                $('#start_time').attr('disabled', false);
                $('#end_time').attr('disabled', false);
                $('#start_time').attr('required', true);
                $('#end_time').attr('required', true);
            }
        });

        $('.picture-delete').click(function () {
            var event = $(this).data('event');
            event = event.split(',');
            var self = this;
            $.ajax({
                method: "POST",
                data: {type: event[0], eventId: event[1], imageId: event[2]},
                url: "<?php echo url('admin/delete-event-image'); ?>",
                success: function (response) {
                    if (response == 1) {
                        $(self).closest('li').remove();
                        alert('Successfully removed');
                    }
                }
            });
        });
    });
</script>

@endsection
