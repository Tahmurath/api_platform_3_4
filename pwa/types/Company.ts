import { Item } from "./item";

export class Company implements Item {
  public "@id"?: string;

  constructor(_id?: string, public name?: string, public users?: string[]) {
    this["@id"] = _id;
  }
}
