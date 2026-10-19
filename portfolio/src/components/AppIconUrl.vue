<script setup lang="ts">
import { computed } from "vue";
const props = defineProps({
	url: {
		type: String,
		required: true,
	},
	path: {
		type: String,
		required: true,
	},
	alter: {
		type: String,
		required: true,
	},

	colorTheme: {
		type: String,
		required: true,
	},
});

const colorTheme = computed(() => props.colorTheme);
</script>

<template>
	<a :href="props.url" target="_blank" class="icon-link">
		<picture>
			<source type="image/avif" :srcset="`/graphics/url/${props.path}-24-${colorTheme}.avif 24w, /graphics/url/${props.path}-48-${colorTheme}.avif 48w, /graphics/url/${props.path}-96-${colorTheme}.avif 96w`" sizes="0.85rem" />

			<source type="image/webp" :srcset="`/graphics/url/${props.path}-24-${colorTheme}.webp 24w, /graphics/url/${props.path}-48-${colorTheme}.webp 48w, /graphics/url/${props.path}-96-${colorTheme}.webp 96w`" sizes="0.85rem" />

			<img class="logo" :srcset="`/graphics/url/${props.path}-24-${colorTheme}.png 24w, /graphics/url/${props.path}-48-${colorTheme}.png 48w, /graphics/url/${props.path}-96-${colorTheme}.png 96w`" sizes="0.85em" :src="`/graphics/url/${props.path}-48-${colorTheme}.png`" :alt="props.alter" loading="lazy" decoding="async" />
		</picture>
	</a>
</template>

<style scoped>
img {
	width: auto;
	vertical-align: bottom;
	height: 0.85em;
	aspect-ratio: 1 / 1;
}
</style>
