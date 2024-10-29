import { GetStaticPaths, GetStaticProps } from "next";
import { dehydrate, QueryClient } from "react-query";

import {
  PageList,
  getCompanys,
  getCompanysPath,
} from "../../../components/company/PageList";
import { PagedCollection } from "../../../types/collection";
import { Company } from "../../../types/Company";
import { fetch, getCollectionPaths } from "../../../utils/dataAccess";

export const getStaticProps: GetStaticProps = async ({
  params: { page } = {},
}) => {
  const queryClient = new QueryClient();
  await queryClient.prefetchQuery(getCompanysPath(page), getCompanys(page));

  return {
    props: {
      dehydratedState: dehydrate(queryClient),
    },
    revalidate: 1,
  };
};

export const getStaticPaths: GetStaticPaths = async () => {
  const response = await fetch<PagedCollection<Company>>("/companies");
  const paths = await getCollectionPaths(
    response,
    "companies",
    "/companys/page/[page]"
  );

  return {
    paths,
    fallback: true,
  };
};

export default PageList;
