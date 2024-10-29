import { NextComponentType, NextPageContext } from "next";
import { useRouter } from "next/router";
import Head from "next/head";
import { useQuery } from "react-query";

import Pagination from "../common/Pagination";
import { List } from "./List";
import { PagedCollection } from "../../types/collection";
import { Company } from "../../types/Company";
import { fetch, FetchResponse, parsePage } from "../../utils/dataAccess";
import { useMercure } from "../../utils/mercure";

export const getCompanysPath = (page?: string | string[] | undefined) =>
  `/companies${typeof page === "string" ? `?page=${page}` : ""}`;
export const getCompanys = (page?: string | string[] | undefined) => async () =>
  await fetch<PagedCollection<Company>>(getCompanysPath(page));
const getPagePath = (path: string) =>
  `/companys/page/${parsePage("companies", path)}`;

export const PageList: NextComponentType<NextPageContext> = () => {
  const {
    query: { page },
  } = useRouter();
  const { data: { data: companys, hubURL } = { hubURL: null } } = useQuery<
    FetchResponse<PagedCollection<Company>> | undefined
  >(getCompanysPath(page), getCompanys(page));
  const collection = useMercure(companys, hubURL);

  if (!collection || !collection["hydra:member"]) return null;

  return (
    <div>
      <div>
        <Head>
          <title>Company List</title>
        </Head>
      </div>
      <List companys={collection["hydra:member"]} />
      <Pagination collection={collection} getPagePath={getPagePath} />
    </div>
  );
};
