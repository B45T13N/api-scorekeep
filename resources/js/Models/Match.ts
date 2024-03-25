import {LocalTeam} from "./LocalTeam";
import {VisitorTeam} from "./VisitorTeam";
import {Volunteer} from "./Volunteer";

export interface Match {
    uuid: string,
    address: string,
    category: string,
    gameDate: Date,
    isHomeMatch: boolean,
    isCancelled: boolean,
    timekeeper?: Volunteer,
    secretary?: Volunteer,
    roomManager?: Volunteer,
    drinkManager?: Volunteer,
    roomManagers: [],
    secretaries: [],
    timekeepers: [],
    drinkManagers: [],
    localTeam: LocalTeam,
    visitorTeam: VisitorTeam,
    visitorTeamName?: string,
    localTeamId?: number
}