import mysql.connector
import matplotlib.pyplot as plt
import matplotlib.dates as mdates
from datetime import datetime

DATABASE = mysql.connector.connect(
    host="sql126.main-hosting.eu",
    user="u787130504_brian",
    passwd=";+B8CKe&",
    database="u787130504_oceans"
)
pointer = DATABASE.cursor()


def get_current_data():
    pointer.execute(
        "SELECT uk_deaths, uk_cases, usa_deaths, usa_cases FROM coronavirus ORDER BY entry DESC LIMIT 1")
    data = pointer.fetchone()
    pointer.reset(free=True)
    print("UK deaths: {}"
          "\nUK cases: {}"
          "\nUSA deaths: {}"
          "\nUSA cases: {}".format(*data))


get_current_data()


def get_historical_data():
    pointer.execute(
        "SELECT datetime, uk_deaths, uk_cases, usa_deaths, usa_cases FROM coronavirus")
    data = pointer.fetchall()
    pointer.reset(free=True)
    dates = []
    usa_cases = []
    uk_cases = []
    usa_deaths = []
    uk_deaths = []
    for entry in data:
        dates.append(entry[0])
        uk_deaths.append(entry[1])
        usa_deaths.append(entry[3])
        usa_cases.append(entry[4])
        uk_cases.append(entry[2])
    # plt.style.use("fivethirtyeight")
    fig, (ax_d, ax_c) = plt.subplots(2, 1, sharex="col", figsize=(19.2, 10.8))
    plt.title('China Virus')
    ax_c.set_title("Cases", fontsize=20)
    ax_d.set_title("Deaths", fontsize=20)
    ax_c.plot(dates, usa_cases, label="USA cases", linewidth=4, color="#000f61")
    ax_c.plot(dates, uk_cases, label="UK cases", linewidth=4, color="#610000")
    ax_d.plot(dates, usa_deaths, label="USA deaths", linewidth=4, color="#000f61")
    ax_d.plot(dates, uk_deaths, label="UK deaths", linewidth=4, color="#610000")
    ax_c.legend()
    ax_d.legend()
    ax_c.set_ylim(ymin=0)
    ax_d.set_ylim(ymin=0)
    # ax_c.xaxis.set_major_locator(mdates.MonthLocator())
    # ax_c.xaxis.set_major_formatter(mdates.DateFormatter("%b"))
    # ax_c.xaxis.set_minor_locator(mdates.DayLocator())
    # ax_c.xaxis.set_minor_formatter(mdates.DateFormatter("%d"))
    ax_c.grid(b=True, linestyle="--", axis="both")
    ax_d.grid(b=True, linestyle="--", axis="both")
    plt.savefig("graph.png", transparent=True, format="png")


get_historical_data()

