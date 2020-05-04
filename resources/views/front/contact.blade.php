@extends('layouts.front.master')

@section('page')
Contact
@endsection

@push('css')
@endpush

@section('content')

    <div class="row">
                <div class="span4">
                    <aside>
                        <div class="widget">
                            <h4 class="rheading">Get in touch with us<span></span></h4>
                            <ul>
                                <li><label><strong>Phone : </strong></label>
                                    <p>
                                        +880 1772119941
                                    </p>
                                </li>
                                <li><label><strong>Email : </strong></label>
                                    <p>
                                        email@aminurDev.com
                                    </p>
                                </li>
                                <li><label><strong>Adress : </strong></label>
                                    <p>
                                        shewora para, mirpur 21 bulding Dhaka 1216 Bangladesh
                                    </p>
                                </li>
                            </ul>
                        </div>
                        <div class="widget">
                            <h4 class="rheading">Find us on social networks<span></span></h4>
                            <ul class="social-links">
                                <li><a href="#" title="Twitter"><i class="icon-square icon-32 icon-twitter"></i></a></li>
                                <li><a href="#" title="Facebook"><i class="icon-square icon-32 icon-facebook"></i></a></li>
                                <li><a href="#" title="Google plus"><i class="icon-square icon-32 icon-google-plus"></i></a></li>
                                <li><a href="#" title="Linkedin"><i class="icon-square icon-32 icon-linkedin"></i></a></li>
                                <li><a href="#" title="Pinterest"><i class="icon-square icon-32 icon-pinterest"></i></a></li>
                            </ul>
                        </div>
                    </aside>
                </div>
                <div class="span8">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d22864.11283411948!2d-73.96468908098944!3d40.630720240038435!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c24fa5d33f083b%3A0xc80b8f06e177fe62!2sNew+York%2C+NY%2C+USA!5e0!3m2!1sen!2sbg!4v1540447494452" width="100%" height="380" frameborder="0" style="border:0" allowfullscreen></iframe>

                    <div class="spacer30">
                    </div>

                    <div id="success_message"></div>
                    <div id="error_message"></div>
                    <form method="POST" class="contactForm" id="contact_form">
                        @csrf

                        <div class="row">
                            <div class="span4 form-group">
                                <input type="text" name="name" class="input-block-level" id="name" placeholder="Your Name" data-rule="minlen:4" data-msg="Please enter at least 4 chars" />
                                <div class="validation"></div>
                            </div>

                            <div class="span4 form-group">
                                <input type="email" class="input-block-level" name="email" id="email" placeholder="Your Email" data-rule="email" data-msg="Please enter a valid email" />
                                <div class="validation"></div>
                            </div>
                            <div class="span8 form-group">
                                <input type="text" class="input-block-level" name="subject" id="subject" placeholder="Subject" data-rule="minlen:4" data-msg="Please enter at least 8 chars of subject" />
                                <div class="validation"></div>
                            </div>
                            <div class="span8 form-group">
                                <textarea class="input-block-level" name="message" rows="5" data-rule="required" data-msg="Please write something for us" placeholder="Message"></textarea>
                                <div class="validation"></div>
                                <div class="text-center">
                                    <button class="btn btn-theme" type="submit">Send a message</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

@endsection

@push('js')
    <script>
        $(document).ready(function () {
            $("#contact_form").on("submit",function (e) {
                e.preventDefault();

                var _token = $('input[name = "_token"]').val();

                var myData = $('#contact_form').serializeArray();
                console.log(myData);

                $.ajax({
                    url: "{{ route('contact.store') }}",
                    type: "POST",
                    data: $.param(myData),
                    dataType: "json",
                    success: function (data) {
                        if(data.flash_message_success) {
                            $('#success_message').html(' <div class="alert alert-success alert-block">\n' +
                                '                <button type="button" class="close" data-dismiss="alert">x</button>\n' +
                                '               <strong>' + data.flash_message_success + '</strong>\n' +
                                '            </div>');
                        }else {

                            $('#error_message').html(' <div class="alert alert-danger alert-block">\n' +
                                '                <button type="button" class="close" data-dismiss="alert">x</button>\n' +
                                '               <strong>' + data.error + '</strong>\n' +
                                '            </div>');
                        }

                        $("form").trigger("reset");

                        $('.form-group').find('.valids').hide();
                    },

                    error : function (err) {
                        if (err.status == 422) {

                            $.each(err.responseJSON.errors, function (i, error) {
                                var el = $(document).find('[name="'+i+'"]');
                                el.after($('<span class="valids" style="color: red;">'+error+'</span>'));
                            });
                        }
                    }
                });
            })
        })
    </script>
@endpush