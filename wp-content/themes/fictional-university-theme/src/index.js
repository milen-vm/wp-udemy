// Wordpress js package handles scss files, in build folder they are converted to css.
import "../css/style.scss"

// Our modules / classes
import MobileMenu from "./modules/MobileMenu"
import HeroSlider from "./modules/HeroSlider"
import OSMap from "./modules/OSMap"

// Instantiate a new object using our modules/classes
const mobileMenu = new MobileMenu()
const heroSlider = new HeroSlider()
const openStreetMap = new OSMap()
