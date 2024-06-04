<section id="comment" class="ftco-section ftco-no-pt ftco-no-pb ftco-counter img bg-primary" id="section-counter"
    data-stellar-background-ratio="0.5">
    <div class="container">
        <div class="row">
            <div class="col-md-6 py-5 pr-md-5">
                <div class="heading-section heading-section-white ftco-animate mb-5">
                    <h2 class="mb-4">Comment</h2>

                    <p>Give us a testimonial of your experience when using our services.</p>
                </div>

                <form action="/comment" method="POST" class="appointment-form ftco-animate">
                    @csrf

                    <div class="d-md-flex">
                        <div class="form-group">
                            <textarea name="comment" id="" cols="30" rows="7" class="form-control" placeholder="Message"
                                required></textarea>
                        </div>

                        <div class="form-group ml-md-4">
                            <button type="submit" class="btn btn-secondary py-3 px-4">Submit</button>
                        </div>
                    </div>

                    @error('comment')
                        <div class="alert alert-danger mt-2">
                            {{ $message }}
                        </div>
                    @enderror

                    @if (session()->has('success'))
                        <div class="d-flex justify-content-center mt-3">
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        </div>
                    @endif

                </form>
            </div>

            <div class="col-lg-6 p-5 bg-counter aside-stretch">
                <h3 class="vr">About Loggingpedia Facts</h3>
                <div class="row pt-4 mt-1">
                    <div class="col-md-6 d-flex justify-content-center counter-wrap ftco-animate">
                        <div class="block-18 p-5 bg-light">
                            <div class="text">
                                <strong class="number" data-number="30">0</strong>
                                <span>Years of Experienced</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 d-flex justify-content-center counter-wrap ftco-animate">
                        <div class="block-18 p-5 bg-light">
                            <div class="text">
                                <strong class="number" data-number="4500">0</strong>
                                <span>Number of Users</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 d-flex justify-content-center counter-wrap ftco-animate">
                        <div class="block-18 p-5 bg-light">
                            <div class="text">
                                <strong class="number" data-number="84">0</strong>
                                <span>Number of Engineer</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 d-flex justify-content-center counter-wrap ftco-animate">
                        <div class="block-18 p-5 bg-light">
                            <div class="text">
                                <strong class="number" data-number="300">0</strong>
                                <span>Number of Staffs</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
