( function($){
  $(document).ready(function(){
  if($('#sa_carousel_grid').hasClass('carousel'))
  {
    const $carouselItem = $('.sa_pr_cat_grid_list_item');
    $carouselItem.addClass('carousel-column');
    const itemWidth = $carouselItem.outerWidth();
    const itemsCount = $carouselItem.length;
    const itemsPerPage = 1;
    const currentitem = $('.currentitem');
    let currentIndex = 0;

    $('#next').on('click', function() {
        if (currentIndex + itemsPerPage < itemsCount) {
            currentIndex += itemsPerPage;
            updateCarousel();
        }

        if(currentIndex + itemsPerPage == itemsCount)
        {
          backtoStart();
          currentIndex = 0;
        }
        
    });

    $('#prev').on('click', function() {
        if (currentIndex > 0) {
            currentIndex -= itemsPerPage;
            updateCarousel();
        }
    });

    function updateCarousel() {
        const translateX = -currentIndex * itemWidth;

        $carouselItem.animate({'left':`${translateX}px`},700,'linear');
    }

    function backtoStart(){
      $carouselItem.animate({'left':0},700,'linear');
    }
  } else {
    $carouselItem.removeClass('carousel-column');
    return;
  }


});

})(jQuery);