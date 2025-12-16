(function () {
    let yt_play = document.querySelector('div.yt-embed .vid-play-btn');

        if(yt_play) {
            let yt_iframe = document.querySelector('div.yt-embed iframe');
            let yt_iframe_src = yt_iframe.getAttribute('data-src');
            let yt_wrapper = document.querySelector('div.yt-embed .vid-overlay-wrapper');
            
            yt_play.addEventListener('click', function() {
                yt_wrapper.style.display = "none";
                yt_iframe.setAttribute('src', yt_iframe_src);
            });
        }
    
    window.addEventListener("load", () => {
        let galleryElem = document.getElementById('my-gallery');
        if( galleryElem === undefined || galleryElem === null ) {
            return;
        }

        let galleryLoaderInitial = document.querySelector('.gallery-loader-initial');
        galleryLoaderInitial.style.display = "none";
        galleryElem.style.display = "block";
        let currentItemCount = galleryElem.childElementCount;
        let galleryDataElem = document.getElementById('gallery-data');
        if( galleryDataElem === undefined ) {
            return;
        }

        let galleryDataJson = galleryDataElem.innerHTML;
        let galleryData = JSON.parse(galleryDataJson);

        let loaderElem = document.querySelector('.gallery-loader');

        let bird_names = [];
        
        galleryData.forEach(element => {
            bird_names.push(element.caption);
        });

        const viewPortWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;

        let justifiedHeight = 180;

        if(viewPortWidth < 800) {
            justifiedHeight = 120;
        }


        let loading = false;
        
        $(window).scroll(function() {
            if(loading) {
                return;
            }
            if(Math.round($(window).scrollTop() + $(window).height()) >= Math.round($(document).height() - $(window).height()/3)) {
                let remainingCount = galleryData.length - currentItemCount;
                if(remainingCount === 0) {
                    return;
                }
                let moreCount = (remainingCount > 10) ? 10 : remainingCount;
                let loadedImages = 0;
                loaderElem.style.display = "block";
                loading = true;
                for (let i = currentItemCount; i<currentItemCount+moreCount; i++) {
                    let liElem = document.createElement('li');
                    let figElem = document.createElement('figure');
                    let aElem = document.createElement('a');
                    let imgElem = document.createElement('img');
                    let captionElem = document.createElement('figcaption');
                    aElem.title = galleryData[i].caption;
                    imgElem.style.opacity = 0;
                    imgElem.src = galleryData[i].data_src;
                    imgElem.alt = galleryData[i].caption;
                    imgElem.srcset = galleryData[i].data_srcset;
                    imgElem.sizes = '(min-width: 800px) 33vw, (min-width: 1280px) 25vw, 100vw';
                    captionElem.innerHTML = galleryData[i].caption;
                    aElem.href = galleryData[i].href;

                    aElem.appendChild(imgElem);
                    figElem.appendChild(aElem);
                    figElem.appendChild(captionElem);
                    liElem.appendChild(figElem);

                    galleryElem.appendChild(liElem);

                    imgElem.onload = () => {
                        imgElem.setAttribute('data-width', imgElem.naturalWidth);
                        imgElem.setAttribute('data-height', imgElem.naturalHeight);
                        loadedImages++;
                        if(loadedImages === moreCount) {
                            setTimeout(() => {
                                $('#my-gallery').justifiedGallery('norewind');
                                loading = false;
                                loaderElem.style.display = "none";
                            }, 0);
                            
                        }
                    }    
                }

                currentItemCount = currentItemCount + moreCount;
                
            }
        });

        $('#my-gallery').justifiedGallery({
            selector: 'li',
            rowHeight: justifiedHeight,
            margins: 3,
        }).on('jg.complete', function () {

            galleryElem.querySelectorAll('img').forEach((elem) => {
                elem.style.opacity = 1;
            });

            // Initialize Colorbox AFTER layout finishes
            $('#my-gallery a').colorbox({
                rel: 'my-gallery',
                maxWidth: '95%',
                maxHeight: '95%',
                opacity: 0.8,
                photo: true,
            });

        });;
    });

    /**
     * home faq
     */

    let homeFaqs = document.querySelectorAll('#home-faq .faq-q');
    let homeFaqAnswers = document.querySelectorAll('#home-faq .faq-ans');

    if(homeFaqs !== undefined && homeFaqs !== null) {

        homeFaqs.forEach((faq) => {
            faq.addEventListener('click', (event) => {
                let currentAns = event.currentTarget.nextElementSibling;
                currentAns.classList.toggle('show');
            })
        })
    }

})();