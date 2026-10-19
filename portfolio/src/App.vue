<script setup lang="ts">
import { ref, watch, onMounted } from "vue";
import AppNavbar from "./components/AppNavbar.vue";
import AppSection from "./components/AppSection.vue";
import AppNFooter from "./components/AppFooter.vue";
import AppReadMask from "./components/AppReadMask.vue";
import AppImageView from "./components/AppImageView.vue";
import AppAccessibility from "./components/AppAccessibility.vue";
import AppStatusUpdate from "./components/AppStatusUpdate.vue";
import { useAlerts } from "./components/useAlerts.ts";
const { addAlert } = useAlerts();

const translations = ref<Record<string, any>>({});
const lang = ref<"en" | "pl">((localStorage.getItem("userLanguage") as "en" | "pl") || "en");

watch(lang, (newLang) => {
	localStorage.setItem("userLanguage", newLang);
	document.documentElement.lang = newLang;
});

function translate() {
	if (lang.value == "en") {
		lang.value = "pl";
		addAlert("Ustawiono język na polski", 3000, true);
	} else {
		lang.value = "en";
		addAlert("The language has been set to English", 3000, true);
	}
}

async function getIP(): Promise<"en" | "pl"> {
	try {
		const response = await fetch("/ipregion.php");
		if (!response.ok) throw new Error(`Status: ${response.status}`);

		const country = (await response.text()).trim().toLowerCase();
		return country === "pl" ? "pl" : "en";
	} catch (error: any) {
		console.error(error.message);
		return "en";
	}
}
async function getTranslations() {
	try {
		const response = await fetch("/fetchJson.php?file=translations");
		if (!response.ok) throw new Error(`Status: ${response.status}`);
		translations.value = await response.json();
	} catch (error: any) {
		console.error(`Error loading translations: ${error.message}`);
	}
}
async function init() {
	const savedLang = localStorage.getItem("userLanguage");
	if (savedLang === "en" || savedLang === "pl") {
		lang.value = savedLang;
	} else {
		lang.value = await getIP();
	}
	await getTranslations();
	loadAccessibility();
}

const accessibility = ref<Record<string, any>>({
	colorScheme: 0,
	contrast: 0,
	reducedMotion: 0,
	reducedTransparency: 0,
	reducedColors: 0,
	readingMask: 0,
	adjustText: 0,
});
const showFilter = ref<boolean>(true);
const readingMask = ref<boolean>(false);
const showReadingMask = ref<boolean>(false);
const showImagePreview = ref<boolean>(false);
const showImagePreviewURL = ref<string>("aria_welcome");
const hasAvif = ref<boolean>(false);
const ImagePreviewAlt = ref<string>("false");
const accessibilityVisibility = ref<boolean>(false);
const colorTheme = ref<string>("dark");

function updateAccessibilityVisibility(value: Boolean) {
	accessibilityVisibility.value = Boolean(value);
	if (!value && readingMask.value && !showImagePreview.value) {
		showReadingMask.value = true;
	} else showReadingMask.value = false;

	if (value) {
		if (lang.value == "en") {
			addAlert("The accessibility settings has been opened", 3000, true);
		} else addAlert("Otworzono ustawienia dostępności", 3000, true);
	} else {
		if (lang.value == "en") {
			addAlert("The accessibility settings has been closed", 3000, true);
		} else addAlert("Zamknięto ustawienia dostępności", 3000, true);
	}
}

function updateImagePreviewVisibility(value: Boolean) {
	showImagePreview.value = Boolean(value);

	if (showImagePreview.value) {
		updateAccessibilityVisibility(false);
		showReadingMask.value = false;
	} else {
		showReadingMask.value = readingMask.value && !accessibilityVisibility.value;
	}
}

function setImagePreview(url: string, hasAvifValue: boolean, alt: string) {
	hasAvif.value = hasAvifValue;
	showImagePreviewURL.value = url;
	ImagePreviewAlt.value = alt;
	updateImagePreviewVisibility(true);
}

function updateAccessibility(name: string, index: Number, showAlert: Boolean = false) {
	const buttonData = translations.value.accessibility?.[lang.value]?.buttons?.[Number(index)];
	const currentValue = Number(accessibility.value[name]) || 0;
	const newValue = (currentValue + 1) % buttonData.values.length;
	accessibility.value[name] = String(newValue);
	localStorage.setItem(buttonData.emitFunction, String(newValue));
	set(buttonData.emitFunction, newValue, showAlert);
}

function setAlert(name: string, value: number) {
	const alertsData = translations.value?.accessibility?.[lang.value]?.alerts;
	if (!alertsData) return;
	const category = alertsData[name];
	if (!category) return;
	const valueText = category.values[value];
	if (valueText === undefined) return;

	const isOnOff = ["on", "off", "włączony", "wyłączony", "włączona", "wyłączona", "włączone", "wyłączone"].includes(valueText.toLowerCase());
	const verb = isOnOff ? category.verbAlt || alertsData.secondPartAlt : category.verb || alertsData.secondPart;

	const firstPart = alertsData.firstPart ? `${alertsData.firstPart} ` : "";
	const message = `${firstPart}${category.name} ${verb} ${valueText}`.trim();

	addAlert(message);
}

function set(name: string, value: number, showAlert: Boolean = false) {
	let className: string = "";
	if (name == "colorScheme") {
		document.documentElement.classList.remove("dark", "light");
		if (value == 0) {
			className = window.matchMedia("(prefers-color-theme: light)").matches ? "light" : "dark";
		} else className = value == 1 ? "light" : "dark";
		colorTheme.value = className;
	} else if (name == "contrast") {
		document.documentElement.classList.remove("defaultContrast", "lessContrast", "moreContrast");
		switch (value) {
			case 0:
				if (window.matchMedia("(prefers-contrast: no-preference)").matches || window.matchMedia("(prefers-contrast: custom)").matches) {
					className = "defaultContrast";
				} else if (window.matchMedia("(prefers-contrast: less)").matches) {
					className = "lessContrast";
				} else className = "moreContrast";
				break;

			case 1:
				className = "defaultContrast";
				break;

			case 2:
				className = "lessContrast";
				break;

			case 3:
				className = "moreContrast";
				break;

			default:
				break;
		}
	} else if (name == "reducedMotion") {
		document.documentElement.classList.remove("noReducedMotion", "reducedMotion");
		if (value == 0) {
			className = window.matchMedia("(prefers-reduced-motion: no-preference)").matches ? "noReducedMotion" : "reducedMotion";
		} else className = value == 1 ? "noReducedMotion" : "reducedMotion";
	} else if (name == "reducedTransparency") {
		document.documentElement.classList.remove("noReducedTransparency", "reducedTransparency");
		if (value == 0) {
			className = window.matchMedia("(prefers-reduced-transparency: reduce)").matches ? "reducedTransparency" : "noReducedTransparency";
			showFilter.value = window.matchMedia("(prefers-reduced-transparency: reduce)").matches ? false : true;
		} else {
			className = value == 1 ? "noReducedTransparency" : "reducedTransparency";
			showFilter.value = value == 1 ? true : false;
		}
	} else if (name == "reducedColors") {
		document.documentElement.classList.remove("noReducedColors", "reducedColors");
		if (value == 0) {
			className = "noReducedColors";
		} else className = "reducedColors";
	} else if (name == "readingMask") {
		document.documentElement.classList.remove("noReadingMask", "readingMask");
		const numValue = Number(value);
		if (numValue === 0) {
			className = "noReadingMask";
			readingMask.value = false;
		} else {
			className = "readingMask";
			readingMask.value = true;
		}
	} else if (name == "adjustText") {
		document.documentElement.classList.remove("normalText", "biggerText", "biggerTextPlus", "smallerText", "smallerTextPlus");
		switch (value) {
			case 0:
				className = "normalText";
				break;

			case 1:
				className = "biggerText";
				break;

			case 2:
				className = "biggerTextPlus";
				break;

			case 3:
				className = "smallerText";
				break;

			case 4:
				className = "smallerTextPlus";
				break;

			default:
				break;
		}
	}
	document.documentElement.classList.add(className);
	if (showAlert) setAlert(name, value);
}

function loadAccessibility() {
	if (!localStorage.getItem("colorScheme")) localStorage.setItem("colorScheme", "0");
	if (!localStorage.getItem("contrast")) localStorage.setItem("contrast", "0");
	if (!localStorage.getItem("reducedMotion")) localStorage.setItem("reducedMotion", "0");
	if (!localStorage.getItem("reducedTransparency")) localStorage.setItem("reducedTransparency", "0");
	if (!localStorage.getItem("reducedColors")) localStorage.setItem("reducedColors", "0");
	if (!localStorage.getItem("readingMask")) localStorage.setItem("readingMask", "0");
	if (!localStorage.getItem("adjustText")) localStorage.setItem("adjustText", "0");

	set("colorScheme", Number(localStorage.getItem("colorScheme")));
	accessibility.value.colorScheme = localStorage.getItem("colorScheme");
	set("contrast", Number(localStorage.getItem("contrast")));
	accessibility.value.contrast = localStorage.getItem("contrast");
	set("reducedMotion", Number(localStorage.getItem("reducedMotion")));
	accessibility.value.reducedMotion = localStorage.getItem("reducedMotion");
	set("reducedTransparency", Number(localStorage.getItem("reducedTransparency")));
	accessibility.value.reducedTransparency = localStorage.getItem("reducedTransparency");
	set("reducedColors", Number(localStorage.getItem("reducedColors")));
	accessibility.value.reducedColors = localStorage.getItem("reducedColors");
	set("readingMask", Number(localStorage.getItem("readingMask")));
	accessibility.value.readingMask = localStorage.getItem("readingMask");
	set("adjustText", Number(localStorage.getItem("adjustText")));
	accessibility.value.adjustText = localStorage.getItem("adjustText");

	showReadingMask.value = readingMask.value && !accessibilityVisibility.value && !showImagePreview.value;
}
init();

onMounted(() => {
	window.addEventListener("keydown", (event) => {
		{
			if (event.key === "Escape") {
				if (accessibilityVisibility.value) {
					updateAccessibilityVisibility(false);
				} else if (showImagePreview.value) {
					updateImagePreviewVisibility(false);
				}
			}
		}
	});
});
</script>

<template>
	<div id="appContainer" v-if="translations.navbar?.[lang]">
		<AppNavbar :translation="translations.navbar?.[lang]" @changeLanguage="translate" />
		<AppSection sectionId="hero" :translation="translations.hero?.[lang]" :isArticleList="false" :isDashed="false" :isHero="true" @setImagePreview="setImagePreview" />
		<AppSection sectionId="featuredProjects" :translation="translations.featuredProjects?.[lang]" :colorTheme="colorTheme" @setImagePreview="setImagePreview" />
		<AppSection sectionId="lab" :translation="translations.lab?.[lang]" :colorTheme="colorTheme" @setImagePreview="setImagePreview" />
		<AppSection sectionId="aboutAria" :translation="translations.aboutAria?.[lang]" :isArticleList="false" :isDashed="false" @setImagePreview="setImagePreview" />
		<AppNFooter :translation="translations.footer?.[lang]" />
		<AppReadMask :isActivated="showReadingMask" />
		<AppImageView :showFilter="showFilter" :isActivated="showImagePreview" :url="showImagePreviewURL" :ImagePreviewAlt="ImagePreviewAlt" @updateImagePreview="updateImagePreviewVisibility" :hasAvif="hasAvif" :translation="translations.imagePreview?.[lang]" />
		<AppAccessibility :translation="translations.accessibility?.[lang]" :valuesTable="accessibility" :showFilter="showFilter" :showMenu="accessibilityVisibility" :colorTheme="colorTheme" @updateAccessibility="updateAccessibility" @showAccessibilityMenu="updateAccessibilityVisibility" />
		<AppStatusUpdate />
	</div>
</template>
