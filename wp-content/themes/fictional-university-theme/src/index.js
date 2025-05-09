// Wordpress js package handles scss files, in build folder they are converted to css.
import "../css/style.scss"

// Our modules / classes
import MobileMenu from "./modules/MobileMenu"
import HeroSlider from "./modules/HeroSlider"
import OSMap from "./modules/OSMap"
import Search from "./modules/Search"
import MyNotes from "./modules/MyNotes"
import Like from "./modules/Like"

// Instantiate a new object using our modules/classes
const mobileMenu = new MobileMenu()
const heroSlider = new HeroSlider()
const openStreetMap = new OSMap()
const siteSearch = new Search()
const myNotes = new MyNotes()
const like = new Like()
