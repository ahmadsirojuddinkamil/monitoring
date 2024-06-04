<section id="testimoni" class="ftco-section testimony-section bg-light">
    <div class="container">
        <div class="row justify-content-center mb-5 pb-2">
            <div class="col-md-8 text-center heading-section ftco-animate">
                <span class="subheading">Testimonials</span>

                <h2 class="mb-4">Our Users Say About Us</h2>

                <p>Our services are already used by many people. And all of them feel the significant benefits of the
                    services we create.</p>
            </div>
        </div>

        <div class="row ftco-animate justify-content-center">
            <div class="col-md-8">
                <div class="carousel-testimony owl-carousel">
                    @forelse ($comments as $comment)
                        <div class="item">
                            <div class="testimony-wrap d-flex">
                                <div class="user-img mr-4"
                                    style="background-image: url('assets/home/images/person_1.jpg')"></div>
                                <div class="text ml-2 bg-light">
                                    <span class="quote d-flex align-items-center justify-content-center">
                                        <i class="icon-quote-left"></i>
                                    </span>
                                    <p>{{ $comment->comment }}</p>
                                    <p class="name">{{ $comment->username }}</p>
                                    <span class="position">Member</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p>No comments found.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
