<template>
    <div ref="sliderRef"
         class="slider w-full h-full flex"
         :class="[`slider-${id}`,{
            'flex-col': !horizontal,
            'overflow-hidden': isScrolling || disableUserInteraction,
            'overflow-y-auto': !isScrolling && !horizontal && !disableUserInteraction,
            'overflow-x-auto': !isScrolling && horizontal && !disableUserInteraction
         }]"
    >
        <SliderSlide v-for="(slide, index) in slides"
                     :key="slide.name"
                     :name="`slide-${slide.name}-${index}`"
                     :class="`slider-slide-${id} slide-${slide.name}`"
        >
            <slot :name="slide.name" />
        </SliderSlide>
    </div>
</template>
<script>
import SliderSlide from '@/Elements/Slider/Slide';
import {nextTick, onMounted, reactive, ref, watch} from 'vue';

export default {
    name: 'Slider',
    components: {
        SliderSlide
    },
    props: {
        slides: {
            type: Array,
            required: true
        },
        minOffset: {
            type: Number,
            default: 50,
        },
        speed: {
            type: Number,
            default: 50
        },
        horizontal: Boolean,
        disableUserInteraction: Boolean,
    },

    setup(props, { emit }) {
        const id = Math.random().toString(16).slice(2);
        const sliderRef = ref(null);
        const slides = reactive([]);
        const position = ref(0);
        const activeSlideIndex = ref(0);
        const interacting = ref(false);
        const direction = ref(null);
        const isScrolling = ref(false);
        const offsetDirection = props.horizontal ? 'offsetLeft' : 'offsetTop';
        const scrollDirection = props.horizontal ? 'scrollLeft' : 'scrollTop';

        function setup() {
            if (!props.disableUserInteraction) {
                sliderRef.value.addEventListener('touchstart', onTouchStart, false);
                sliderRef.value.addEventListener('scroll', onScroll, false);
            }

            slides.splice(0, slides.length);

            Array.prototype.slice.call(sliderRef.value.querySelectorAll(`.slider-slide-${id}`)).forEach((slide) => {
                slides.push(slide);
            });
        }

        function onTouchStart() {
            if (isScrolling.value) return;
            interacting.value = true;
            sliderRef.value.addEventListener('touchend', onTouchEnd, false);
        }

        function onTouchEnd() {
            sliderRef.value.removeEventListener('touchend', onTouchEnd);

            switch (direction.value) {
                case 'next':
                    scrollToNextSlide();
                    break;
                case 'prev':
                    scrollToPrevSlide();
                    break;
                default:
                    scrollBackToCurrent();
            }
        }

        function onScroll() {
            if (!interacting.value) {
                return scroll();
            }

            const currentPosition = sliderRef.value[scrollDirection];
            let offset = 0;
            if (currentPosition > position.value) {
                offset = currentPosition - position.value;
                direction.value = offset >= props.minOffset ? 'next' : null;
            } else {
                offset = position.value - currentPosition;
                direction.value = offset >= props.minOffset ? 'prev' : null;
            }
        }

        function scrollBackToCurrent() {
            scrollToActiveSlide();
        }

        function scrollTo(index) {
            activeSlideIndex.value = index;
            scroll();
        }

        function scrollToNextSlide() {
            const length = slides.length - 1;
            if (activeSlideIndex.value >= length) return;
            activeSlideIndex.value += 1;
            scroll();
        }

        function scrollToPrevSlide() {
            if (activeSlideIndex.value <= 0) return;
            activeSlideIndex.value -= 1;
            scroll();
        }

        function scrollToActiveSlide() {
            scroll();
        }

        function scroll() {
            isScrolling.value = true;
            const slidePos = slides[activeSlideIndex.value][offsetDirection];
            const currPos = sliderRef.value[scrollDirection];
            const positiveOffsetValue = Math.abs(currPos > slidePos ? currPos - slidePos : slidePos - currPos);
            if (positiveOffsetValue <= props.speed) {
                setSlideFinalPosition();
                return;
            }
            const value = slidePos > currPos ? props.speed : -props.speed;
            sliderRef.value[scrollDirection] += value;
            requestAnimationFrame(scroll);
        }

        function setSlideFinalPosition() {
            position.value = slides[activeSlideIndex.value][offsetDirection];
            sliderRef.value[scrollDirection] = position.value;
            isScrolling.value = false;
            direction.value = null;
            interacting.value = false;
            emit('change', props.slides[activeSlideIndex.value].name, activeSlideIndex.value);
        }


        watch(
            props.slides,
            () => {
                sliderRef.value.removeEventListener('touchstart', onTouchStart);
                sliderRef.value.removeEventListener('scroll', onScroll);
                nextTick(() => {
                    setup();
                    setSlideFinalPosition();
                });
            }
        );

        onMounted(() => {
            nextTick(() => {
                setup();

                const index = props.slides.findIndex(slide => slide.active);
                activeSlideIndex.value = index !== -1 ? index : 0;
                setSlideFinalPosition();
            });
        });

        return {
            id,
            sliderRef,
            isScrolling,
            scrollTo,
            slideTo(name) {
                const index = props.slides.findIndex(slide => slide.name === name);
                if (index === -1) return;
                scrollTo(index);
            },
            next() {
                const nextIndex = activeSlideIndex.value + 1;
                if (nextIndex >= props.slides.length) return;
                scrollTo(nextIndex);
            },
            back() {
                const nextIndex = activeSlideIndex.value - 1;
                if (nextIndex < 0) return;
                scrollTo(nextIndex);
            }
        }
    }
}
</script>
