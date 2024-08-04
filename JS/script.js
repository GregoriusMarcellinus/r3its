const allSideMenu = document.querySelectorAll('#sidebar .side-menu.top li a');

allSideMenu.forEach(item=> {
	const li = item.parentElement;

	item.addEventListener('click', function () {
		allSideMenu.forEach(i=> {
			i.parentElement.classList.remove('active');
		})
		li.classList.add('active');
	})
});



// TOGGLE SIDEBAR
const menuBar = document.querySelector('#content nav .bx.bx-menu');
const sidebar = document.getElementById('sidebar');

menuBar.addEventListener('click', function () {
	sidebar.classList.toggle('hide');
})

const searchButton = document.querySelector('#content nav form .form-input button');
const searchButtonIcon = document.querySelector('#content nav form .form-input button .bx');
const searchForm = document.querySelector('#content nav form');

searchButton.addEventListener('click', function (e) {
	if(window.innerWidth < 576) {
		e.preventDefault();
		searchForm.classList.toggle('show');
		if(searchForm.classList.contains('show')) {
			searchButtonIcon.classList.replace('bx-search', 'bx-x');
		} else {
			searchButtonIcon.classList.replace('bx-x', 'bx-search');
		}
	}
})
if(window.innerWidth < 768) {
	sidebar.classList.add('hide');
} else if(window.innerWidth > 576) {
	searchButtonIcon.classList.replace('bx-x', 'bx-search');
	searchForm.classList.remove('show');
}


window.addEventListener('resize', function () {
	if(this.innerWidth > 576) {
		searchButtonIcon.classList.replace('bx-x', 'bx-search');
		searchForm.classList.remove('show');
	}
})
const switchMode = document.getElementById('switch-mode');

switchMode.addEventListener('change', function () {
	if(this.checked) {
		document.body.classList.add('dark');
	} else {
		document.body.classList.remove('dark');
	}
})
const timeSpan = document.querySelector("#timeSpan");

const optionsTime = {
  timeZone: 'Asia/Jakarta',
  timeZoneName: 'short',
  hour: '2-digit',
  hour12: 'true',
  minute: 'numeric',
};

const formatter = new Intl.DateTimeFormat([], optionsTime);
updateTime();
setInterval(updateTime, 1000);

function updateTime() {
	const dateTime = new Date();
	const formattedDateTime = formatter.format(dateTime);
	timeSpan.textContent = formattedDateTime;
}


// tanggal
const timeElement = document.querySelector(".time");
const dateElement = document.querySelector(".date");

/**
 * @param {Date} date
 */
function formatTimeWithPeriods(date) {
  const hours24 = date.getHours();
  const minutes = date.getMinutes();
  let period;

  if (hours24 >= 0 && hours24 < 6) {
    period = "Malam";
  } else if (hours24 < 12) {
    period = "Pagi";
  } else if (hours24 < 18) {
    period = "Siang";
  } else {
    period = "Malam";
  }

  const hours12 = hours24 % 12 || 12;

  return `${hours12.toString().padStart(2, "0")}:${minutes
    .toString()
    .padStart(2, "0")} ${period}`;
}

/**
 * @param {Date} date
 */
function formatDate(date) {
  const DAYS = [
    "Minggu",
    "Senin",
    "Selasa",
    "Rabu",
    "Kamis",
    "Jum'at",
    "Sabtu"
  ];
  const MONTHS = [
    "Januari",
    "Februari",
    "Maret",
    "April",
    "Mei",
    "Juni",
    "Juli",
    "Agustus",
    "September",
    "Oktober",
    "Nopember",
    "Desember"
  ];

  return `${DAYS[date.getDay()]}, ${date.getDate()}  ${
    MONTHS[date.getMonth()]
  } ${date.getFullYear()}`;
}

setInterval(() => {
  const now = new Date();

  timeElement.textContent = formatTimeWithPeriods(now);
  dateElement.textContent = formatDate(now);
}, 200);

//sapaan
const greetingElement = document.querySelector(".greeting");

/**
 * @param {Date} date
 */
function getGreetingPeriod(date) {
  const hours24 = date.getHours();

  if (hours24 >= 0 && hours24 < 6) {
    return "Malam";
  } else if (hours24 < 12) {
    return "Pagi";
  } else if (hours24 < 18) {
    return "Siang";
  } else {
    return "Malam";
  }
}

/**
 * @param {Date} date
 */
function getGreetingMessage(date) {
  const period = getGreetingPeriod(date);

  switch (period) {
    case "Pagi":
      return "Selamat Pagi!";
    case "Siang":
      return "Selamat Siang!";
    case "Malam":
      return "Selamat Malam!";
    default:
      return "!";
  }
}

setInterval(() => {
  const now = new Date();
  const greetingMessage = getGreetingMessage(now);

  greetingElement.textContent = greetingMessage;
}, 200);