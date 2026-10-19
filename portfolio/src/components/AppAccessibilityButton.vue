<script setup lang="ts">
import AppHeader from "./AppHeader.vue";

interface ButtonData {
	name: string;
	imgSrc: string;
	imgAlt: string;
	emitFunction: string;
	values: Record<string | number, string>;
}

const props = defineProps<{
	buttonData: ButtonData;
	index: number;
	valuesTable: Record<string, any>;
	colorTheme: string;
}>();

defineEmits<{
	(e: "updateAccessibilityButton", emitFunction: string, index: number): void;
}>();
</script>

<template>
	<div class="setting">
		<div class="topImage">
			<picture>
				<source type="image/avif" :srcset="`/graphics/${props.buttonData.imgSrc}-200-${props.colorTheme}.avif 1x, /graphics/${props.buttonData.imgSrc}-400-${props.colorTheme}.avif 2x, /graphics/${props.buttonData.imgSrc}-600-${props.colorTheme}.avif 3x`" />
				<source type="image/webp" :srcset="`/graphics/${props.buttonData.imgSrc}-200-${props.colorTheme}.webp 1x, /graphics/${props.buttonData.imgSrc}-400-${props.colorTheme}.webp 2x, /graphics/${props.buttonData.imgSrc}-600-${props.colorTheme}.webp 3x`" />
				<img :srcset="`/graphics/${props.buttonData.imgSrc}-200-${props.colorTheme}.png 1x, /graphics/${props.buttonData.imgSrc}-400-${props.colorTheme}.png 2x, /graphics/${props.buttonData.imgSrc}-600-${props.colorTheme}.png 3x`" :src="`/graphics/${props.buttonData.imgSrc}-400-${props.colorTheme}.png`" :alt="props.buttonData.imgAlt" loading="lazy" decoding="async" />
			</picture>
		</div>

		<div class="name" :id="`setting-title-${props.index}`">
			<AppHeader :content="props.buttonData.name" :type="3" />
		</div>

		<button class="setValue" type="button" :aria-label="props.buttonData.name" @click="$emit('updateAccessibilityButton', props.buttonData.emitFunction, props.index)">
			<span aria-hidden="true">
				{{ props.buttonData.values[props.valuesTable[props.buttonData.emitFunction]] }}
			</span>
		</button>
	</div>
</template>

<style scoped>
div.setting {
	background-color: var(--bg-accessibility-grid);
	/* Elastyczna proporcja: kwadrat, ale bez blokowania minimalnej wysokości */
	aspect-ratio: 1 / 1;
	min-height: 110px; /* Bezpieczny punkt podparcia na bardzo małych ekranach */

	border: solid 2px var(--accent-color);
	border-radius: 12%; /* Procentowy border-radius bywa jajowaty na prostokątach, 12% jest stabilniejsze */

	display: flex;
	flex-direction: column;
	text-align: center;
	overflow: hidden;

	/* Wsparcie Container Queries */
	container-type: inline-size; /* 'inline-size' zamiast 'size' zapobiega pętli layoutu w Safari */
	container-name: setting-box;

	box-sizing: border-box;
}

div.topImage,
div.name h3 {
	pointer-events: none;
}

div.topImage,
div.name h3,
button.setValue {
	user-select: none;
	-webkit-user-select: none; /* Safari fix */
}

/* Obrazek i kontener */
div.topImage {
	flex: 1 1 0%;
	min-height: 0;
	padding: 4cqw 4cqw 2cqw;
	display: flex;
	justify-content: center;
	align-items: center;
}

div.topImage picture {
	display: block;
	align-items: center;
	justify-content: center;
	width: 100%;
	height: 100%;
	min-height: 0;
}

div.topImage img {
	max-height: 100%;
	max-width: 100%;
	width: auto;
	height: auto;
	object-fit: contain;
}

div.name {
	flex: 0 0 auto;
	padding: 2cqw 4cqw;
	font-weight: 500;
}

div.name :deep(h3) {
	font-size: clamp(0.7rem, 9cqw, 1.1rem);
	line-height: 1.15;
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
	margin: 0;
}

button.setValue {
	flex: 0 0 auto;
	padding: 3cqw 2cqw;
	display: flex;
	align-items: center;
	justify-content: center;
	font-size: clamp(0.65rem, 8cqw, 0.95rem);

	border-top: solid 2px var(--accent-color);
	background-color: rgba(0, 173, 181, 0.1);
	cursor: pointer;
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
	color: var(--text);
	border-left: none;
	border-right: none;
	border-bottom: none;
}

button.setValue span {
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
	width: 100%;
}

/* Media queries bez zmian */
@media (pointer: fine) {
	div.setting:hover {
		background-color: var(--bg-accessibility-grid-hover);
	}
	button.setValue:hover {
		background-color: var(--accent-hover);
	}
	html.light button.setValue:hover {
		color: var(--bg-primary);
	}
	html.moreContrast div.setting:hover div.name,
	html.moreContrast div.setting:hover button.setValue {
		color: var(--bg-primary);
	}
	html.moreContrast div.setting:hover button.setValue:hover,
	html.moreContrast.dark div.setting:hover button.setValue:hover {
		background-color: var(--text);
	}
	html.moreContrast div.setting:hover button.setValue {
		background-color: var(--accent-hover);
	}
	html.moreContrast.dark div.setting:hover button.setValue {
		background-color: #3c91b6;
	}
}

@media (pointer: coarse) {
	div.setting:active {
		background-color: var(--bg-accessibility-grid-hover);
	}
	button.setValue:active {
		background-color: var(--accent-hover);
	}
	html.light button.setValue:active {
		color: var(--bg-primary);
	}
}
</style>