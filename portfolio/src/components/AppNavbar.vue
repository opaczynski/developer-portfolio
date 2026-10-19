<script setup lang="ts">
export interface NavbarTranslation {
  featuredProjects?: string;
  lab?: string;
  aboutAria?: string;
  flagImage?: string;
  translate?: string;
  translateTo?: string;
  [key: string]: any;
}

defineProps<{
  translation?: NavbarTranslation;
}>();

const emit = defineEmits<{
  (e: "changeLanguage"): void;
}>();
</script>

<template>
  <header id="top">
    <div id="headerLeft">
      <h1>
        <a href="/">Jakub Opaczyński</a>
      </h1>
    </div>
    <div id="headerRight">
      <nav>
        <span>
          <a href="#featuredProjects">{{ translation?.featuredProjects || 'Featured Projects' }}</a>
          <span class="dot" aria-hidden="true"> • </span>
        </span>

        <span>
          <a href="#lab">{{ translation?.lab || 'Lab' }}</a>
          <span class="dot" aria-hidden="true"> • </span>
        </span>

        <span>
          <a href="#aboutAria">{{ translation?.aboutAria || 'About Aria' }}</a>
          <span class="dot" aria-hidden="true"> • </span>
        </span>

        <button @click="emit('changeLanguage')">
          <picture>
            <source
              type="image/avif"
              :srcset="`/graphics/${translation?.flagImage || 'flags/en'}-100.avif 100w, /graphics/${translation?.flagImage || 'flags/en'}-200.avif 200w`"
              sizes="60px"
            />
            <source
              type="image/webp"
              :srcset="`/graphics/${translation?.flagImage || 'flags/en'}-100.webp 100w, /graphics/${translation?.flagImage || 'flags/en'}-200.webp 200w`"
              sizes="60px"
            />
            <img
              :srcset="`/graphics/${translation?.flagImage || 'flags/en'}-100.png 100w, /graphics/${translation?.flagImage || 'flags/en'}-200.png 200w`"
              sizes="60px"
              :src="`/graphics/${translation?.flagImage || 'flags/en'}-200.png`"
              :alt="translation?.translate || 'Zmień język na polski'"
              loading="lazy"
              decoding="async"
              :lang="translation?.translateTo || 'pl'"
            />
          </picture>
        </button>
      </nav>
    </div>
  </header>
</template>

<style scoped>
header {
  padding-top: max(calc(0.5 * var(--font-base)), env(safe-area-inset-bottom));
  display: flex;
}

header:before {
  bottom: 0;
}

div#headerLeft {
  min-width: max-content;
}

div#headerRight {
  flex-grow: 1;
  justify-content: flex-end;
  align-items: center;
  display: flex;
}

span.dot {
  pointer-events: none;
}

button {
  background: 0 0;
  border: none;
  margin: 0;
  font-size: 1em;
}

button:hover {
  cursor: pointer;
}

img {
  vertical-align: middle;
  width: auto;
  height: 1.2em;
}

html.light[lang="pl"] img {
  box-sizing: content-box;
  border: 1px solid var(--text);
}

@media (width <= 767px) {
  header {
    padding: calc(0.5 * var(--font-base));
    flex-direction: column;
  }

  div#headerLeft,
  div#headerRight,
  nav {
    text-align: center;
    min-width: 100%;
  }

  div#headerRight {
    padding: calc(0.3 * var(--font-base)) 0;
  }
}</style>