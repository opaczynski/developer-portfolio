<script setup lang="ts">
import { computed, ref, watch, nextTick } from "vue";
import AppHeader from "./AppHeader.vue";
import AppAccessibilityButton from "./AppAccessibilityButton.vue";

const props = defineProps({
	showFilter: {
		type: Boolean,
		required: true,
	},
	isActivated: {
		type: Boolean,
		required: true,
	},
	url: {
		type: String,
		default: "aria/aria_bust_up",
		required: false,
	},
	hasAvif: {
		type: Boolean,
		required: true,
	},
	ImagePreviewAlt: {
		type: String,
		required: true,
	},
	translation: {
		type: Object,
		required: true,
	},
});

const emit = defineEmits<{
	updateImagePreview: [newValue: Boolean];
}>();

const showPreview = computed(() => props.isActivated);
const url = computed(() => props.url);
const imageAlt = computed(() => props.ImagePreviewAlt);
const translation = computed(() => props.translation);

// Zmieniamy referencję na element <img> zamiast kontenera div
const imageRef = ref<HTMLImageElement | null>(null);
const closeButtonRef = ref<HTMLButtonElement | null>(null);

function handleTabKey(e: KeyboardEvent) {
	if (!showPreview.value) return;

	const imgEl = imageRef.value;
	const closeBtn = closeButtonRef.value;

	if (!imgEl || !closeBtn) return;

	const activeEl = document.activeElement;

	if (e.shiftKey) {
		if (activeEl === imgEl) {
			e.preventDefault();
			closeBtn.focus();
		}
	} else {
		if (activeEl === closeBtn) {
			e.preventDefault();
			imgEl.focus();
		}
	}
}

watch(showPreview, async (newValue) => {
	if (newValue) {
		await nextTick();
		imageRef.value?.focus();
	}
});

function handleImagePreview() {
	let newValue: Boolean;
	if (showPreview.value) {
		newValue = false;
	} else {
		newValue = true;
	}
	emit("updateImagePreview", newValue);
}
</script>

<template>
	<div class="overlay" id="accessibilityMenu" :style="`backdrop-filter: ` + (props.showFilter && showPreview ? `blur(calc(0.5 * var(--font-base)))` : `none`) + `; pointer-events: ` + (showPreview ? `all` : `none`)">
		<div v-if="showPreview" @click="handleImagePreview" id="clickable"></div>

		<div v-if="showPreview" id="imageContainer">
			<picture>
				<source v-if="hasAvif" type="image/avif" :srcset="`/graphics/${url}-preview.avif`" />
				<source type="image/webp" :srcset="`/graphics/${url}-preview.webp`" />
				<img ref="imageRef" tabindex="-1" @keydown.tab="handleTabKey" :src="`/graphics/${url}-preview.png`" loading="lazy" decoding="async" :alt="imageAlt" />
			</picture>
		</div>

		<div v-if="showPreview" id="closeButton" @keydown.tab="handleTabKey">
			<button ref="closeButtonRef" :aria-label="translation.exit" @keydown.enter="handleImagePreview" @click="handleImagePreview">X</button>
		</div>
	</div>
</template>

<style scoped>
div.overlay {
	z-index: 2;
	pointer-events: none;
	display: flex;
	justify-content: center;
	align-items: center;
}

div#clickable {
	width: 100%;
	height: 100%;
	position: fixed;
	z-index: -1;
	cursor: pointer;
}

div#closeButton {
	z-index: 2;
	position: fixed;
	width: 100vw;
	left: 0;
	bottom: calc(0.8 * var(--font-base));
	display: flex;
	justify-content: center;
}

div#closeButton button {
	border: solid 2px var(--accent-color);
	padding: calc(0.3 * var(--font-base)) calc(1.2 * var(--font-base));
	font-size: calc(1.2 * var(--font-base));
	border-radius: calc(0.5 * var(--font-base));
	display: inline-flex;
	align-items: center;
	justify-content: center;
	gap: calc(0.5 * var(--font-base));
	background-color: rgba(0, 173, 181, 0.1);
	color: var(--text);
	cursor: pointer;
	font-weight: 500;
	transition:
		background-color 0.15s ease,
		color 0.15s ease,
		border-color 0.15s ease;
}

@media (pointer: fine) {
	div#closeButton button:hover {
		background-color: var(--accent-hover);
		color: var(--text);
	}

	html.light div#closeButton button:hover {
		color: var(--bg-primary);
	}

	html.moreContrast div#closeButton button {
		background-color: var(--accent-hover);
		color: var(--bg-primary);
	}

	html.moreContrast div#closeButton button:hover {
		background-color: var(--text);
		border-color: var(--text);
		color: var(--bg-primary);
	}

	html.moreContrast.dark div#closeButton button {
		background-color: #3c91b6;
		color: var(--text);
	}

	html.moreContrast.dark div#closeButton button:hover {
		background-color: var(--text);
		border-color: var(--text);
		color: var(--bg-primary);
	}
}

@media (pointer: coarse) {
	div#closeButton button:active {
		background-color: var(--accent-hover);
	}

	html.light div#closeButton button:active {
		color: var(--bg-primary);
	}
}

div#imageContainer {
	z-index: 1;
	max-width: 1200px;
	overflow: hidden;
	background-color: var(--bg-accessibility);
	border: solid 2px var(--accent-color);
}

img {
	display: block;
	outline: none; /* Usuwa domyślną, często nieestetyczną ramkę przeglądarki przy focusie */
}

@media screen and (orientation: portrait) {
	div#imageContainer {
		max-width: 90vw;
	}
	img {
		width: 100%;
	}
}

@media screen and (orientation: landscape) {
	div#imageContainer {
		max-height: 90vh;
	}
	img {
		width: 100%;
		max-height: 85vh;
	}
}
</style>
