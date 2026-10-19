<script setup lang="ts">
import { computed } from "vue";
import AppHeader from "./AppHeader.vue";
import AppList from "./AppList.vue";
import AppIconUrl from "./AppIconUrl.vue";

const props = defineProps({
	sectionId: {
		type: String,
		default: "",
	},
	translation: {
		type: Object,
		required: true,
	},
	isArticleList: {
		type: Boolean,
		default: true,
	},
	isDashed: {
		type: Boolean,
		default: true,
	},
	isHero: {
		type: Boolean,
		default: false,
	},
	colorTheme: {
		type: String,
		required: false,
		default: "dark",
	},
});

const emit = defineEmits<{
	(e: "setImagePreview", imgSrc: string, hasAvif: boolean, alt: string): void;
}>();

function setClasses() {
	let classes: string = "";
	if (props.isDashed) classes += "dashed";
	if (props.isDashed && props.isHero) classes += " ";
	if (props.isHero) classes += "hero";
	return classes;
}

const colorTheme = computed(() => props.colorTheme);
</script>

<template>
	<section :class="setClasses()" :id="sectionId">
		<AppHeader v-if="!isHero" :content="translation.title" />
		<article v-if="isArticleList" v-for="project in translation.projects">
			<div class="left">
				<AppHeader :content="project.title" :type="3" :lang="project.titleLang">
					<template #special>
						<AppIconUrl :url="project.url" :path="project.urlIcon" :alter="project.urlAlt" :colorTheme="colorTheme" />
					</template>
				</AppHeader>
				<p>{{ project.description }}</p>

				<AppList :title="translation.features" :list="project.features" />
				<AppList :title="translation.lessons" :list="project.lessons" />
			</div>
			<div class="right">
				<div class="imgContainer">
					<picture>
						<source v-if="project.hasAvif" type="image/avif" :srcset="`/graphics/${project.imgSrc}-200.avif 200w, /graphics/${project.imgSrc}-400.avif 400w, /graphics/${project.imgSrc}-600.avif 600w, /graphics/${project.imgSrc}-900.avif 900w`" sizes="(min-width: 1920px) 300px, (min-width: 768px) 20vw, 80vw" />

						<source type="image/webp" :srcset="`/graphics/${project.imgSrc}-200.webp 200w, /graphics/${project.imgSrc}-400.webp 400w, /graphics/${project.imgSrc}-600.webp 600w, /graphics/${project.imgSrc}-900.webp 900w`" sizes="(min-width: 1920px) 300px, (min-width: 768px) 20vw, 80vw" />

						<img tabindex="0" role="button" :srcset="`/graphics/${project.imgSrc}-200.png 200w, /graphics/${project.imgSrc}-400.png 400w, /graphics/${project.imgSrc}-600.png 600w, /graphics/${project.imgSrc}-900.png 900w`" sizes="(min-width: 1920px) 300px, (min-width: 768px) 20vw, 80vw" :src="`/graphics/${project.imgSrc}-600.png`" :alt="project.imgAlt + '. ' + translation.altEnding" loading="lazy" decoding="async" @keydown.enter="$emit('setImagePreview', project.imgSrc, project.hasAvif, project.imgAlt)" @click="$emit('setImagePreview', project.imgSrc, project.hasAvif, project.imgAlt)" />
					</picture>
				</div>
			</div>
		</article>
		<article v-else>
			<div class="left">
				<AppHeader v-if="isHero" :content="translation.title" />
				<p v-for="text in translation.description">{{ text }}</p>

				<p v-if="props.sectionId == 'aboutAria'" class="credit">
					{{ translation.credit }}:
					<a href="https://vgen.co/Kaibeexx" target="_blank" lang="en">Kaibeexx</a>
				</p>
			</div>
			<div class="right">
				<div class="imgContainer">
					<picture>
						<source v-if="translation.hasAvif" type="image/avif" :srcset="`/graphics/${translation.imgSrc}-200.avif 200w, /graphics/${translation.imgSrc}-400.avif 400w, /graphics/${translation.imgSrc}-600.avif 600w, /graphics/${translation.imgSrc}-900.avif 900w`" sizes="(min-width: 1920px) 300px, (min-width: 768px) 20vw, 80vw" />

						<source type="image/webp" :srcset="`/graphics/${translation.imgSrc}-200.webp 200w, /graphics/${translation.imgSrc}-400.webp 400w, /graphics/${translation.imgSrc}-600.webp 600w, /graphics/${translation.imgSrc}-900.webp 900w`" sizes="(min-width: 1920px) 300px, (min-width: 768px) 20vw, 80vw" />

						<img tabindex="0" role="button" :srcset="`/graphics/${translation.imgSrc}-200.png 200w, /graphics/${translation.imgSrc}-400.png 400w, /graphics/${translation.imgSrc}-600.png 600w, /graphics/${translation.imgSrc}-900.png 900w`" sizes="(min-width: 1920px) 300px, (min-width: 768px) 20vw, 80vw" :src="`/graphics/${translation.imgSrc}-600.png`" :alt="translation.imgAlt + '. ' + translation.altEnding" loading="lazy" decoding="async" @keydown.enter="$emit('setImagePreview', translation.imgSrc, translation.hasAvif, translation.imgAlt)" @click="$emit('setImagePreview', translation.imgSrc, translation.hasAvif, translation.imgAlt)" />
					</picture>
				</div>
			</div>
		</article>
	</section>
</template>

<style scoped>
section {
	width: var(--section-width);
	margin: auto;
	margin-top: calc(2.5 * var(--font-base));
}

p {
	margin-top: calc(0.5 * var(--font-base));
}
p.credit {
	margin-top: var(--font-base);
}

article {
	display: flex;
	flex-direction: row;
	position: relative;
	padding-bottom: var(--font-base);
	margin-top: var(--font-base);
}
article:first {
	margin-top: 0;
}
article:last-of-type {
	padding-bottom: 0;
}
article::before {
	content: "";
	width: calc(100% + calc(0.5 * var(--font-base)));
	height: calc(2.5 * var(--font-base));
	position: absolute;
	bottom: 0;
	left: calc(-0.5 * var(--font-base));
}

div.left {
	width: var(--left-width);
}
div.right {
	width: var(--right-width);
	flex-grow: 1;
	display: flex;
	justify-content: center;
	align-items: flex-end;
}

div.imgContainer {
	display: flex;
	justify-content: center;
	width: var(--img-width);
}
img {
	width: 100%;
	cursor: pointer;
}

/* Dashed - every article has underline */
section.dashed article::before {
	border-bottom: 1px solid var(--accent-color);
	border-left: 1px solid var(--accent-color);
}
section.dashed article:last-of-type::before {
	border-bottom: transparent;
	border-left: transparent;
}

/* Hero styling - everything is cented in Y-axis*/
section.hero h2::before {
	background-color: transparent;
}
section.hero p:first-of-type {
	margin-top: 0;
}
section.hero div.left {
	flex-direction: column;
	justify-content: center;
}
section.hero div.right {
	align-items: center;
}
section.hero div.imgContainer {
	width: var(--img-hero-width);
}

/*Accessibility*/
@media (max-width: 767px) {
	article {
		flex-direction: column;
	}
	div.left,
	div.right {
		width: 100%;
		padding: 0 calc(1.5 * var(--font-base));
		justify-content: center;
		min-width: 0;
	}
	div.imgContainer {
		height: auto;
		aspect-ratio: 16 / 9;
		margin: var(--font-base) 0 0;
		width: 100%;
		overflow: hidden;
		position: relative;
	}

	/* FIREFOX & SAFARI FIX */
	picture {
		display: flex;
		justify-content: center;
		align-items: center;
		width: 100%;
		height: 100%;
	}

	img {
		width: 100%;
		height: 100%;
		max-width: 100%;
		max-height: 100%;
		object-fit: contain;
		margin: 0 auto;
	}

	article::before {
		border-left: transparent;
		border-bottom: transparent;
		background-color: var(--accent-color);
		width: calc(var(--img-width) + 10%);
		height: 2px;
		left: calc((100% - var(--img-width) - 10%) / 2);
	}
	article:last-of-type::before {
		background-color: transparent;
	}
}
</style>
