import { Item } from "./item";

export class User implements Item {
  public "@id"?: string;

  constructor(
    _id?: string,
    public email?: string,
    public plainPassword?: string,
    public role?: string,
    public name?: string,
    public company?: string
  ) {
    this["@id"] = _id;
  }
}
