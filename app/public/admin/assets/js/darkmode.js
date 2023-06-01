import { html, svg, svgUse } from "./elements";

const getTheme = async function() {
    const theme = await localStorage.getItem('theme');
    return theme;
}

const setTheme = async function(theme) {
    localStorage.setItem('theme', theme);
    let fillAttr = (theme === 'dark') ? 'white' : 'black';
    let svgUseHref = (theme === 'dark') ? '#moon-fill' : '#light-fill';

    html.setAttribute('data-bs-theme', theme);
    svg.setAttribute('fill', fillAttr);
    svgUse.setAttribute('href', svgUseHref);
}

const initTheme = async function() {
    const savedTheme = await getTheme();
    console.log(savedTheme);

    if(savedTheme) {
        await setTheme(savedTheme);
    }
    else {
        await setTheme('light');
    }

}

const themeTogglerOnClick = async function(event) {
    
    const currentTheme =  await getTheme();
    
    if(currentTheme === 'light') {
        await setTheme('dark');
    }
    else {
        await setTheme('light');
    }
}

export { initTheme, themeTogglerOnClick };